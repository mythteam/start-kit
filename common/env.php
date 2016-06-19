<?php
(new \Dotenv\Dotenv(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR))->load();

if (!function_exists('env'))
{
    function env($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            return $default;
        }

        switch ($value) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }
        if (strlen($value) > 1 && \yii\helpers\StringHelper::startsWith($value, '"')
        && \yii\helpers\StringHelper::endsWith($value, '"')) {
            return substr($value, 1, -1);
        }
        return $value;
    }
}


defined('YII_DEBUG') or define('YII_DEBUG', env('YII_DEBUG'));
defined('YII_ENV') or define('YII_ENV', env('YII_ENV') ?: 'prod');
