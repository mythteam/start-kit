<?php

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => require __DIR__ . '/_modules.php',
    'language' => 'zh-CN',
    'homeUrl' => FRONT_URL,
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => Yaconf::get('kit.frontend.cookie_vk'),
        ],
        'user' => [
            'identityClass' => common\models\User::class,
            'enableAutoLogin' => true,
        ],
        'session' => [
            'name' => '_sid',
        ],
        'cache' => [
            'keyPrefix' => 'frontend',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'ruleConfig' => ['class' => 'yii\web\UrlRule', 'host' => FRONT_URL],
            // 'cache' => 'cache',
            'hostInfo' => FRONT_URL,
            'rules' => require '_routes.php'
        ],
        'assetManager' => [
            'class' => yii\web\AssetManager::class,
            'appendTimestamp' => !YII_ENV_PROD,
        ],
    ],
    'params' => array_merge(
        require (__DIR__ . '/../../common/config/params.php'),
        require (__DIR__ . '/params.php')
    ),
];
