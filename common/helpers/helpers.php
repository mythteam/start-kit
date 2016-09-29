<?php

use yii\helpers\Html;
use yii\helpers\Url;

if (!function_exists('image')) {
    function image($file, $options = [])
    {
        return Html::img(Yii::$app->request->getHostInfo() . '/images/' . $file, $options);
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
 * Get file alias.
 *
 * @param string $alias
 *
 * @return bool|string
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
 *
 * @param mixed $type
 * @param array $params
 *
 * @return object
 */
function make($type, array $params = [])
{
    return \Yii::createObject($type, $params);
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
 * user()->isGuest ? echo 'Hello guest' : echo 'Hello baby';
 * ~~~
 *
 * @return \yii\web\User
 */
function user()
{
    return \Yii::$app->getUser();
}

/**
 * @param string|array $url
 * @param bool|string  $scheme
 *
 * @return string
 */
function url($url = '', $scheme = false)
{
    return Url::to($url, $scheme);
}

