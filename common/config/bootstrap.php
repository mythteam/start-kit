<?php

defined('DS') or define('DS', DIRECTORY_SEPARATOR);
define('FRONT_URL', Yaconf::get('kit.frontend.url'));
define('FRONTEND_DOMAIN', Yaconf::get('kit.frontend.domain'));
define('BACKEND_URL', Yaconf::get('kit.backend.url'));
define('API_URL', Yaconf::get('kit.api.url'));

Yii::setAlias('common', dirname(__DIR__));
Yii::setAlias('frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('console', dirname(dirname(__DIR__)) . '/console');

//set default pagination page size to 10
Yii::$container->set(yii\data\Pagination::class, [
    'defaultPageSize' => 10,
]);
