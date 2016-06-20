<?php

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => require '_modules.php',
    'language' => 'zh-CN',
    'homeUrl' => env('BACKEND_URL'),
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => env('BACKEND_COOKIE_VALIDATION_KEY'),
        ],
        'user' => [
            'identityClass' => common\models\Webmaster::class,
            'enableAutoLogin' => true,
            'as afterLogin' => backend\components\behaviors\AfterLoginBehavior::class,
            'authTimeout' => 3600, //auto logout 60 mins
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
            // 'cache' => 'cache',
            //'hostInfo' => env('app.BACKEND_URL'),
            'rules' => require '_routes.php'
        ],
        'assetManager' => [
            'class' => yii\web\AssetManager::class,
            'appendTimestamp' => !YII_ENV_PROD,
            'bundles' => require 'asset-bundles.php',
        ],
        'view' => [
            'theme' => [
                'basePath' => '@app/themes/sbadmin',
                'baseUrl' => '@web/themes/sbadmin',
                'pathMap' => [
                    '@app/views' => '@app/themes/sbadmin',
                ],
            ],
        ],
    ],
    'params' => array_merge(
        require (__DIR__ . '/../../common/config/params.php'),
        require (__DIR__ . '/params.php')
    ),
    'as access' => [
        'class' => yii\filters\AccessControl::class,
        'except' => ['site/login'],
        'rules' => [
            ['allow' => true, 'roles' => ['@']],
        ],
    ],
];
