<?php

if (!function_exists('__gv')) {
    function __gv($array, $key, $default = null)
    {
        if (is_array($key)) {
            $lastKey = array_pop($key);
            foreach ($key as $keyPart) {
                $array = __gv($array, $keyPart);
            }
            $key = $lastKey;
        }

        if (is_array($array) && array_key_exists($key, $array)) {
            return $array[$key];
        }

        if (($pos = strrpos($key, '.')) !== false) {
            $array = __gv($array, substr($key, 0, $pos), $default);
            $key = substr($key, $pos + 1);
        }

        if (is_array($array)) {
            return array_key_exists($key, $array) ? $array[$key] : $default;
        } else {
            return $default;
        }
    }

}


if (!function_exists('env')) {
    function env($key, $default = null)
    {
        static $ini;
        if (null === $ini) {
            $ini = parse_ini_file(__DIR__ . '/../env.ini', true);
        }

        return __gv($ini, $key, $default);
    }
}


defined('YII_DEBUG') or define('YII_DEBUG', env('YII_DEBUG') == '1');
defined('YII_ENV') or define('YII_ENV', env('YII_ENV') ?: 'prod');
