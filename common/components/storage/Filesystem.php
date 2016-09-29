<?php

namespace common\components\storage;

use League\Flysystem\AdapterInterface;
use Yii;
use yii\base\InvalidParamException;

class Filesystem extends \League\Flysystem\Filesystem implements FilesystemInterface
{
    /**
     * Write the file contents to a file path.
     *
     * @param string $path
     * @param string $filePath
     * @param array  $config
     *
     * @return bool
     */
    public function putFile($path, $filePath, array $config = [])
    {
        $resource = fopen(Yii::getAlias($filePath), 'r+');
        $result = parent::putStream($path, $resource, $config);
        fclose($resource);

        return $result;
    }

    /**
     * Get the visibility for the given path.
     *
     * @param string $path
     *
     * @return string
     */
    public function getVisibility($path)
    {
        if (parent::getVisibility($path) == AdapterInterface::VISIBILITY_PUBLIC) {
            return self::VISIBILITY_PUBLIC;
        }

        return self::VISIBILITY_PRIVATE;
    }

    public function setVisibility($path, $visibility)
    {
        return parent::setVisibility($path, $this->pareseVisibility($visibility));
    }

    /**
     * {@inheritdoc}
     */
    public function prepend($path, $data)
    {
        if ($this->has($path)) {
            return $this->put($path, $data . PHP_EOL . $this->get($path));
        }

        return $this->put($path, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function append($path, $data)
    {
        if ($this->has($path)) {
            return $this->put($path, $this->get($path) . PHP_EOL . $data);
        }

        return $this->put($path, $data);
    }

    /**
     * @param string $paths
     *
     * @return bool
     */
    public function delete($paths)
    {
        $paths = is_array($paths) ? $paths : func_get_args();

        foreach ($paths as $path) {
            parent::delete($path);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function move($from, $to)
    {
        return parent::rename($from, $to);
    }

    /**
     * {@inheritdoc}
     */
    public function size($path)
    {
        return parent::getSize($path);
    }

    /**
     * @param string $path
     *
     * @return bool|false|string
     */
    public function mimeType($path)
    {
        return parent::getMimetype($path);
    }

    /**
     * {@inheritdoc}
     */
    public function lastModified($path)
    {
        return parent::getTimestamp($path);
    }

    /**
     * {@inheritdoc}
     */
    public function files($directory = null, $recursive = false)
    {
        $contents = parent::listContents($directory, $recursive);

        return $this->filterContentsByType($contents, 'file');
    }

    /**
     * {@inheritdoc}
     */
    public function allFiles($directory = null)
    {
        return $this->files($directory, true);
    }

    /**
     * {@inheritdoc}
     */
    public function directories($directory = null, $recursive = false)
    {
        $contents = parent::listContents($directory, $recursive);

        return $this->filterContentsByType($contents, 'dir');
    }

    /**
     * {@inheritdoc}
     */
    public function allDirectories($directory = null)
    {
        return $this->directories($directory, true);
    }
    
    /**
     * @param array $contents
     * @param string $type
     */
    protected function filterContentsByType($contents, $type)
    {
    }

    /**
     * @param string $visibility
     *
     * @return string|void
     */
    protected function parseVisibility($visibility)
    {
        if (is_null($visibility)) {
            return;
        }
        switch ($visibility) {
            case FilesystemInterface::VISIBILITY_PUBLIC:
                return AdapterInterface::VISIBILITY_PUBLIC;
            case FilesystemInterface::VISIBILITY_PRIVATE:
                return AdapterInterface::VISIBILITY_PRIVATE;
        }
        throw new InvalidParamException('Unknown visibility: ' . $visibility);
    }
    
    /**
     * @param string $path
     *
     * @return string
     */
    public function getUrl($path)
    {
        return $this->getAdapter()->getUrl($path);
    }
    
    /**
     * @inheritdoc
     */
    public function getFileKey($path)
    {
        return $this->getAdapter()->getFileKey($path);
    }
}
