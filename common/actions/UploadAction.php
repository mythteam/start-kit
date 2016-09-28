<?php

namespace common\actions;

use common\components\storage\Filesystem;
use common\components\storage\Storage;
use FileNamingResolver\FileInfo;
use FileNamingResolver\FileNamingResolver;
use Yii;
use yii\base\Action;
use yii\base\Object;
use yii\di\Instance;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\UploadedFile;

final class UploadAction extends Action
{
    /**
     * @var string The name attribute set to file upload input.
     */
    public $fieldName = 'file_data';
    /**
     * @var string|array|Storage The filesystem configuration.
     */
    public $fs = 'fs';
    
    /**
     * @var string The base path file uploaded to.
     */
    public $uploadBasePath = '@webroot/upload';
    /**
     * @var string The base url.
     */
    public $uploadBaseUrl = '@web/upload';
    /**
     * @var string The url of the action.
     */
    public $actionUrl;
    /**
     * @var string The action.
     */
    protected $action;
    /**
     * @var array
     */
    protected $availableActions = ['upload', 'delete'];
    
    /**
     * @inheritdoc
     */
    final public function init()
    {
        $this->actionUrl = Yii::$app->request->getUrl();
        $this->action = Yii::$app->request->get('action', 'upload');
        $this->fs = Instance::ensure($this->fs, Storage::class);
        
        if ($field = Yii::$app->request->get('field')) {
            $this->fieldName = $field;
        }
        
        parent::init();
    }
    
    /**
     * @inheritdoc
     */
    final public function run()
    {
        if (!in_array($this->action, $this->availableActions)) {
            return ['error' => 'Invalid requested action.'];
        }
        
        return call_user_func([$this, $this->action]);
        
    }
    
    /**
     * Upload function.
     *
     * @return array
     */
    protected function upload()
    {
        $file = UploadedFile::getInstanceByName($this->fieldName);
        if (null === $file) {
            return ['error' => '上传文件失败, 获取实例失败'];
        }
        
        $uploadFileName = $file->baseName;
        /** @var FileNamingResolver $fileNameResolver */
        $fileNameResolver = Yii::createObject(FileNamingResolver::class);
        $fileName = (string)$fileNameResolver->resolve(new FileInfo($file->name));
        
        $resource = fopen($file->tempName, 'r+');
        if (false === $this->fs->putStream($fileName, $resource)) {
            return ['error' => '上传文件失败'];
        }
        fclose($resource);
    
        $url = $this->fs->getUrl($fileName);
    
        //http://plugins.krajee.com/file-input#ajax-uploads
        return [
            'initialPreview' => [
                Html::img(
                    $url,
                    [
                        'class' => 'kv-preview-data file-preview-image',
                        'alt' => $uploadFileName,
                        'title' => $uploadFileName,
                        'style' => 'width:auto;height:160px;'
                    ]
                ),
            ],
            'initialPreviewConfig' => [
                [
                    'caption' => $uploadFileName,
                    'url' => Url::to([$this->actionUrl, 'action' => 'delete'], true),
                    'key' => $fileName,
                    'fileName' => $fileName,
                    'source' => $url,
                ]
            ]
        ];
    }
    
    /**
     * Delete uploaded file function.
     */
    protected function delete()
    {
        $key = Yii::$app->request->post('key');
        
        if (empty($key)) {
            return ['error' => 'File not exists.'];
        }
        $key = trim($key, '/');
        
        if (!$this->fs->has($key)) {
            return [];
        }
        
        if ($this->fs->delete($key)) {
            return [];
        }
        
        return ['error' => 'Delete failure.'];
    }
    
    /**
     * @inheritdoc
     */
    public function beforeRun()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        return parent::beforeRun();
    }
}
