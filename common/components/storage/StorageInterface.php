<?php

namespace common\components\storage;

interface StorageInterface
{
    /**
     * Get the url.
     *
     * @param string $path
     *
     * @return string
     */
    public function getUrl($path);
}
