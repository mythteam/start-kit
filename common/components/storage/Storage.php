<?php

namespace common\components\storage;

use Closure;
use League\Flysystem\AdapterInterface;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * @method bool putStream(string $fileName, $resource, $config = [])
 * @method bool delete(string $path)
 * @method bool has(string $path)
 * @method string getFileKey(string $path)
 * @property Filesystem $disk
 *
 */
class Storage extends Component implements StorageInterface
{
    /**
     * @var string The default Disk
     */
    public $default = 'local';
    /**
     * @var array
     */
    protected $_definitions = [];
    /**
     * @var array
     */
    protected $_disks = [];
    
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }
    
    /**
     * @inheritdoc
     */
    final public function init()
    {
        parent::init();
    }
    
    
    /**
     * @inheritdoc
     */
    public function getUrl($path)
    {
        return $this->disk->getAdapter()->getUrl($path);
    }
    
    /**
     * @param bool $returnDefinitions
     *
     * @return array
     */
    public function getDisks($returnDefinitions = true)
    {
        return $returnDefinitions ? $this->_definitions : $this->_disks;
    }
    
    /**
     * @param array $disks
     */
    public function setDisks(array $disks)
    {
        foreach ($disks as $id => $disk) {
            $this->setDisk($id, $disk);
        }
    }
    
    /**
     * @param int|null $id
     * @param bool $throwException
     *
     * @return mixed|null
     * @throws InvalidConfigException
     */
    public function getDisk($id = null, $throwException = true)
    {
        if (null === $id) {
            $id = $this->default;
        }
        
        if (isset($this->_disks[$id])) {
            return $this->_disks[$id];
        }
        
        if (isset($this->_definitions[$id])) {
            $definition = $this->_definitions[$id];
            if (is_object($definition) && !$definition instanceof Closure) {
                $adapter = $definition;
            } else {
                $adapter = Yii::createObject($definition);
            }
            
            return$this->_disks[$id] = $this->createFilesystem($adapter);
        } elseif ($throwException) {
            throw new InvalidConfigException("Unknown disk ID: {$id}");
        } else {
            return null;
        }
    }
    
    /**
     * @param string $id
     * @param mixed  $definition
     *
     * @throws InvalidConfigException
     */
    public function setDisk($id, $definition)
    {
        if (null === $definition) {
            unset($this->_definitions[$id], $this->_disks[$id]);
            
            return;
        }
        
        if (is_object($definition) || is_callable($definition, true)) {
            $this->_definitions[$id] = $definition;
        } elseif (is_array($definition)) {
            if (isset($definition['class'])) {
                $this->_definitions[$id] = $definition;
            } else {
                throw new InvalidConfigException("The disk configuration for the {$id} component must contain \"class\" element.");
            }
        } else {
            throw new InvalidConfigException("Unexpected configuration for the \"{$id}\" component: " . gettype($definition));
        }
    }
    
    /**
     * @param AdapterInterface $adapter
     * @param array            $config
     *
     * @return Filesystem
     */
    protected function createFilesystem(AdapterInterface $adapter, array $config = [])
    {
        return new Filesystem($adapter, $config);
    }
    
    /**
     * @inheritdoc
     */
    public function __call($name, $params)
    {
        $disk = $this->getDisk();
        if (method_exists($disk, $name)) {
            return call_user_func_array([$disk, $name], $params);
        }
        
        return parent::__call($name, $params);
    }
}
