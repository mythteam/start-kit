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
function data($filename)
{
    return require \Yii::getAlias("@common/data/{$filename}.php");
}

/**
 * Get file alias.
 */
function alias($alias)
{
    return \Yii::getAlias($alias);
}

/**
 * Get Yii::Application or a component.
 *
 * @param string|null $components
 *
 * @return mixed
 */
function app($components = null)
{
    if (null === $components) {
        return \Yii::$app;
    } else {
        return \Yii::$app->get($components);
    }
}
/**
 * 创建类实例.
 *
 * @see Yii::createObject
 */
function make($type, array $params = [])
{
    return \Yii::createObject($tyep, $params);
}

/**
 * @return \yii\web\Request
 */
function request()
{
    return \Yii::$app->getRequest();
}

/**
 * @return \yii\web\Response
 */
function response()
{
    return \Yii::$app->getResponse();
}
/**
 * ~~~
 * user()->isGuest ? echo 'Hello guest' : echo 'Hello boby';
 * ~~~.
 *
 * @return \yii\web\User
 */
function user()
{
    return \Yii::$app->getUser();
}
