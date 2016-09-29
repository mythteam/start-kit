<?php

namespace common\components\storage\adapters;

use Yii;
use yii\base\Configurable;

class Local extends \League\Flysystem\Adapter\Local implements Configurable, AdapterInterface
{
    public $baseUrl;
    
    /**
     * Local constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $root = isset($config['root']) ? $config['root'] : null;
        $writeFlags = isset($config['writeFlags']) ? $config['writeFlags'] : LOCK_EX;
        $linkHandling = isset($config['linkHandling']) ? $config['linkHandling'] : self::DISALLOW_LINKS;
        $permissions = isset($config['permissions']) ? $config['permissions'] : [];
    
        foreach ($config as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
        
        $this->baseUrl = Yii::getAlias($this->baseUrl);

        parent::__construct($root, $writeFlags, $linkHandling, $permissions);
    }

    /**
     * @{@inheritdoc}
     */
    protected function ensureDirectory($root)
    {
        return parent::ensureDirectory(Yii::getAlias($root));
    }
    
    /**
     * @param string $path
     *
     * @return string
     */
    public function getUrl($path)
    {
        return $this->baseUrl . $path;
    }
    
    /**
     * @param string $path
     *
     * @return string
     */
    public function getFileKey($path)
    {
        return str_replace($this->baseUrl, '', $path);
    }
}
