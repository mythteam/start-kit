<?php

namespace common\components\storage;

interface FilesystemInterface
{
    //The public visibility
    const VISIBILITY_PUBLIC = 'public';
    //The private visibility
    const VISIBILITY_PRIVATE = 'private';
    
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
    
    /**
     * Prepend string to a file.
     *
     * @param string $path
     * @param string $data
     *
     * @return int
     */
    public function prepend($path, $data);
    
    /**
     * Append string to a file.
     *
     * @param string $path
     * @param string $data
     *
     * @return int
     */
    public function append($path, $data);
    
    /**
     * Copy a file to new location.
     *
     * @param string $from
     * @param string $to
     *
     * @return bool
     */
    public function copy($from, $to);
    
    /**
     * Move a file to new location.
     *
     * @param string $from
     * @param string $to
     *
     * @return bool
     */
    public function move($from, $to);
    
    /**
     * Get the file size of the given file.
     *
     * @param string $path
     *
     * @return int
     */
    public function size($path);
    
    /**
     * Get the file's last modification time.
     *
     * @param string $path
     *
     * @return string|int
     */
    public function lastModified($path);
    
    /**
     * List all files in a directory.
     *
     * @param null|string $directory
     * @param bool $recursive
     *
     * @return array
     */
    public function files($directory = null, $recursive = false);
    
    /**
     * @param null|string $directory
     *
     * @return array
     */
    public function allFiles($directory = null);
    
    /**
     * @param null|string $directory
     * @param bool $recursive
     *
     * @return array
     */
    public function directories($directory = null, $recursive = false);
    
    /**
     * @param null|string $directory
     *
     * @return mixed
     */
    public function allDirectories($directory = null);
}
