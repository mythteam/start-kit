<?php

namespace common\components\storage\adapters;

interface AdapterInterface
{
    /**
     * @param string $path
     *
     * @return string
     */
    public function getUrl($path);
    
    /**
     * @param string $path
     *
     * @return string
     */
    public function getFileKey($path);
}
