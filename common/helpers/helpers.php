<?php

use yii\helpers\Html;

if (!function_exists('image')) {
    function image($file, $options = [])
    {
        return Html::img(FRONTEDN_URL . '/images/' . $file, $options);
    }
}

if (!function_exists('__')) {
    function __($category, $message, $params = [], $language = null)
    {
        return Yii::t($category, $message, $params, $language);
    }
}

if (!function_exists('t')) {
    function t($category, $message, $params = [], $language = null)
    {
        return Yii::t($category, $message, $params, $language);
    }
}
/**
 * get path with alias.
 *
 * @param string $path
 *
 * @return mixed
 */
function meta_data($filename)
{
    return require Yii::getAlias("@common/metadata/{$filename}.php");
}
