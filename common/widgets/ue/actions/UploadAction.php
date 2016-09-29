<?php

namespace common\widgets\ue\actions;

use common\widgets\ue\actions\uploader\Uploader;
use Yii;
use yii\base\Action;
use yii\base\Exception;
use yii\helpers\Json;

class UploadAction extends Action
{
    /**
     * @var string The target directory uploaded to.
     */
    public $uploadBasePath = '@webroot/upload';
    /**
     * @var string The web access url.
     */
    public $uploadBaseUrl = '@web/upload';
    /**
     * @var bool If enable csrf token validation.
     */
    public $csrf = false;

    /**
     * {filename} 会替换成原文件名,配置这项需要注意中文乱码问题
     * {rand:6} 会替换成随机数,后面的数字是随机数的位数
     * {time} 会替换成时间戳
     * {yyyy} 会替换成四位年份
     * {yy} 会替换成两位年份
     * {mm} 会替换成两位月份
     * {dd} 会替换成两位日期
     * {hh} 会替换成两位小时
     * {ii} 会替换成两位分钟
     * {ss} 会替换成两位秒
     * 非法字符 \ : * ? " < > |.
     *
     * @var array
     */
    public $pathFormat = [
        'imagePathFormat' => '{yyyy}{mm}{dd}/{time}{rand:6}',
        'scrawlPathFormat' => '{yyyy}{mm}{dd}/{time}{rand:6}',
        'snapscreenPathFormat' => '{yyyy}{mm}{dd}/{time}{rand:6}',
        'catcherPathFormat' => '{yyyy}{mm}{dd}/{time}{rand:6}',
        'videoPathFormat' => '{yyyy}{mm}{dd}/{time}{rand:6}',
        'filePathFormat' => '{yyyy}{mm}{dd}/{time}{rand:6}',
    ];
    /**
     * @var array
     */
    public $configPatch = [
        'imageManagerListPath' => '/', //图片列表
        'fileManagerListPath' => '/', //文件列表
    ];
    /**
     * @var array
     */
    public $config = [];
    /**
     * @var string The action name.
     */
    public $action; //request action name
    /**
     * @var string Jsonp callback function name.
     */
    public $jsonpCallback;
    /**
     * @var string The current directory.
     */
    public $currentPath;
    /**
     * @var array
     */
    public $result;
    /**
     * @var \Closure
     */
    public $beforeUpload;
    /**
     * @var \Closure
     */
    public $afterUpload;

    public function init()
    {
        //csrf状态
        Yii::$app->request->enableCsrfValidation = $this->csrf;
        Yii::$app->request->enableCookieValidation = $this->csrf;
        //当前目录
        $this->currentPath = dirname(__FILE__);

        return parent::init();
    }
    
    /**
     * @inheritdoc
     */
    final public function run()
    {
        //load the configuration.
        $this->loadConfig();
        $this->patchConfig();

        $result = $this->dispatch();
        
        if ($this->jsonpCallback) {
            if (preg_match("/^[\w_]+$/", $this->jsonpCallback)) {
                $result = htmlspecialchars($this->jsonpCallback) . '(' . $result . ')';
            } else {
                $result = Json::encode([
                    'state' => 'jsonpCallback参数不合法',
                ]);
            }
        }

        return $result;
    }
    
    /**
     * Run the action for frontend called.
     *
     * @return string
     */
    private function dispatch()
    {
        $_GET['action'] = $this->action;
        $CONFIG = $this->config;
        
        switch ($this->action) {
            case 'config':
                $result = $this->getConfigJson();
                break;
            case 'uploadimage':
            case 'uploadscrawl':
            case 'uploadvideo':
            case 'uploadfile':
                $result = $this->actionUpload($CONFIG);
                break;
            case 'listimage':
            case 'listfile':
                $result = $this->actionList($CONFIG);
                break;
            case 'catchimage':
                $result = $this->actionCrawler($CONFIG);
                break;
            default:
                $result = Json::encode([
                    'state' => '请求地址出错',
                ]);
                break;
        }
        
        return $result;
    }
    
    /**
     * Load the configuration for the config.json
     */
    private function loadConfig()
    {
        $this->config = Json::decode(preg_replace("/\/\*[\s\S]+?\*\//", '', file_get_contents($this->currentPath . '/config.json')), true);
        $this->action = Yii::$app->getRequest()->get('action');
        $this->jsonpCallback = Yii::$app->getRequest()->get('jsonpCallback');
    }
    
    /**
     * 修正路径避免直接修改json config.
     */
    private function patchConfig()
    {
        $uploadBasePath = $this->getUploadBasePath();
        
        foreach ($this->pathFormat as $key => $val) {
            $this->setConfig($key, $val);
        }
        
        foreach ($this->configPatch as $key => $val) {
            if ($this->hasConfig($key)) {
                $this->setConfig($key, $val);
            }
        }
    }

    /**
     * If have the configuration.
     *
     * @param string $name
     *
     * @return bool
     */
    private function hasConfig($name)
    {
        return isset($this->config[$name]) ? true : false;
    }

    /**
     * Get the config.
     *
     * @param string $name
     *
     * @return string
     */
    private function getConfig($name)
    {
        $result = '';
        if ($this->hasConfig($name)) {
            $result = $this->config[$name];
        }

        return $result;
    }

    /**
     * Set configuration.
     *
     * @param string $name
     * @param string $value
     */
    private function setConfig($name, $value)
    {
        $this->config[$name] = $value;
    }
    
    /**
     * 上传存储路径.
     *
     * @param string $url
     *
     * @return string
     */
    private function getUploadBasePath($url = '')
    {
        return rtrim(Yii::getAlias($this->uploadBasePath), '\\/') . '/' . $url;
    }
    
    /**
     * 上传WEB路径.
     *
     * @param string $url
     *
     * @return string
     */
    private function getUploadBaseUrl($url = '')
    {
        return rtrim(Yii::getAlias($this->uploadBaseUrl), '\\/') . '/' . $url;
    }
    
    /**
     * Return the configuration json string to frontend.
     *
     * @return string
     */
    private function getConfigJson()
    {
        $config = $this->config;
        $filterConfig = $this->filterCallback($config);

        return Json::encode($filterConfig);
    }
    
    /**
     * Filter the configruation.
     *
     * @param array $data
     *
     * @return array
     */
    private function filterCallback($data)
    {
        $out = [];
        foreach ($data as $key => $val) {
            if (is_callable($val)) {
                continue;
            }
            if (is_array($val) || is_object($val)) {
                $out[$key] = $this->filterCallback($val);
                continue;
            }
            $out[$key] = $val;
        }

        return $out;
    }
    
    /**
     * @param array $CONFIG
     *
     * @return string
     */
    private function actionCrawler($CONFIG)
    {
        /* 上传配置 */
        $config = [
            'pathFormat' => $CONFIG['catcherPathFormat'],
            'maxSize' => $CONFIG['catcherMaxSize'],
            'allowFiles' => $CONFIG['catcherAllowFiles'],
            'oriName' => 'remote.png',
        ];
        $fieldName = $CONFIG['catcherFieldName'];

        /* 抓取远程图片 */
        $list = [];
        $source = Yii::$app->request->post($fieldName);
        if (!$source) {
            $source = Yii::$app->request->get($fieldName);
        }
        foreach ($source as $imgUrl) {
            //上传基本路径
            $config['uploadBasePath'] = $this->getUploadBasePath();
            $item = new Uploader($imgUrl, $config, 'remote');
            $info = $item->getFileInfo();
            array_push($list, [
                'state' => $info['state'],
                'url' => $this->getUploadBaseUrl($info['url']),
                'size' => $info['size'],
                'title' => htmlspecialchars($info['title']),
                'original' => htmlspecialchars($info['original']),
                'source' => htmlspecialchars($imgUrl),
            ]);
        }

        /* 返回抓取数据 */
        return Json::encode([
            'state' => count($list) ? 'SUCCESS' : 'ERROR',
            'list' => $list,
        ]);
    }

    private function actionList($CONFIG)
    {
        switch ($this->action) {
            case 'listfile':
                $allowFiles = $CONFIG['fileManagerAllowFiles'];
                $listSize = $CONFIG['fileManagerListSize'];
                $path = $CONFIG['fileManagerListPath'];
                break;
            case 'listimage':
            default:
                $allowFiles = $CONFIG['imageManagerAllowFiles'];
                $listSize = $CONFIG['imageManagerListSize'];
                $path = $CONFIG['imageManagerListPath'];
        }
        $allowFiles = substr(str_replace('.', '|', implode('', $allowFiles)), 1);

        $size = Yii::$app->request->get('size', $listSize);
        $size = htmlspecialchars($size);
        $start = Yii::$app->request->get('start', 0);
        $start = htmlspecialchars($start);
        $end = $start + $size;

        $path = $this->getUploadBasePath() . (substr($path, 0, 1) == '/' ? '' : '/') . $path;
        $files = $this->getFiles($path, $allowFiles);
        if (!count($files)) {
            return Json::encode([
                'state' => 'no match file',
                'list' => [],
                'start' => $start,
                'total' => count($files),
            ]);
        }

        /* 获取指定范围的列表 */
        $len = count($files);
        for ($i = min($end, $len) - 1, $list = []; $i < $len && $i >= 0 && $i >= $start; --$i) {
            $list[] = $files[$i];
        }

        /* 返回数据 */
        $result = Json::encode([
            'state' => 'SUCCESS',
            'list' => $list,
            'start' => $start,
            'total' => count($files),
        ]);

        return $result;
    }
    
    /**
     * @param array $CONFIG
     *
     * @return string
     */
    private function actionUpload($CONFIG)
    {
        try {
            if (is_callable($this->beforeUpload)) {
                call_user_func($this->beforeUpload, $this);
            }
        } catch (Exception $e) {
            return Json::encode(['state' => $e->getMessage()]);
        }

        /* 上传配置 */
        $base64 = 'upload';
        switch (htmlspecialchars($this->action)) {
            case 'uploadimage':
                $config = [
                    'pathFormat' => $CONFIG['imagePathFormat'],
                    'maxSize' => $CONFIG['imageMaxSize'],
                    'allowFiles' => $CONFIG['imageAllowFiles'],
                ];
                $fieldName = $CONFIG['imageFieldName'];
                break;
            case 'uploadscrawl':
                $config = [
                    'pathFormat' => $CONFIG['scrawlPathFormat'],
                    'maxSize' => $CONFIG['scrawlMaxSize'],
                    'oriName' => 'scrawl.png',
                ];
                $fieldName = $CONFIG['scrawlFieldName'];
                $base64 = 'base64';
                break;
            case 'uploadvideo':
                $config = [
                    'pathFormat' => $CONFIG['videoPathFormat'],
                    'maxSize' => $CONFIG['videoMaxSize'],
                    'allowFiles' => $CONFIG['videoAllowFiles'],
                ];
                $fieldName = $CONFIG['videoFieldName'];
                break;
            case 'uploadfile':
            default:
                $config = [
                    'pathFormat' => $CONFIG['filePathFormat'],
                    'maxSize' => $CONFIG['fileMaxSize'],
                    'allowFiles' => $CONFIG['fileAllowFiles'],
                ];
                $fieldName = $CONFIG['fileFieldName'];
                break;
        }

        //上传基本路径
        $config['uploadBasePath'] = $this->getUploadBasePath();

        /* 生成上传实例对象并完成上传 */
        $up = new Uploader($fieldName, $config, $base64);

        /*
         * 得到上传文件所对应的各个参数,数组结构
         * array(
         *     "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
         *     "url" => "",            //返回的地址
         *     "title" => "",          //新文件名
         *     "original" => "",       //原始文件名
         *     "type" => ""            //文件类型
         *     "size" => "",           //文件大小
         * )
         */
        /* 返回数据 */
        $result = $up->getFileInfo();
        if (isset($result['url'])) {
            $result['relativePath'] = $result['url'];
            $result['url'] = $this->getUploadBaseUrl($result['url']);
        }
        //set to public for callback
        $this->result = $result;
        if ($result['state'] == 'SUCCESS') {
            try {
                if (is_callable($this->afterUpload)) {
                    call_user_func($this->afterUpload, $this);
                }
            } catch (Exception $e) {
                return Json::encode(['state' => $e->getMessage()]);
            }
        }

        return Json::encode($result);
    }

    /**
     * 遍历获取目录下的指定类型的文件.
     *
     * @param $path
     * @param array $files
     *
     * @return array
     */
    private function getFiles($path, $allowFiles, &$files = [])
    {
        if (!is_dir($path)) {
            return;
        }

        if (substr($path, strlen($path) - 1) != '/') {
            $path .= '/';
        }

        $handle = opendir($path);
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                $path2 = $path . $file;
                if (is_dir($path2)) {
                    $this->getFiles($path2, $allowFiles, $files);
                } else {
                    if (preg_match("/\.(" . $allowFiles . ')$/i', $file)) {
                        $files[] = [
                            'url' => $this->getUploadBaseUrl(substr($path2, strlen($this->getUploadBasePath()))),
                            'mtime' => filemtime($path2),
                        ];
                    }
                }
            }
        }

        return $files;
    }
}
