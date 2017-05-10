<?php

$config = [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => require '_modules.php',
    'language' => 'zh-CN',
    'homeUrl' => BACKEND_URL,
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => Yaconf::get('kit.backend.cookie_vk'),
        ],
        'user' => [
            'class' => backend\components\User::class,
            'identityClass' => common\models\WebMaster::class,
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
            'rules' => require '_routes.php',
        ],
        'assetManager' => [
            'class' => yii\web\AssetManager::class,
            'appendTimestamp' => !YII_ENV_PROD,
            'bundles' => require 'asset-bundles.php',
        ],
        'view' => [
            'theme' => [
                'basePath' => '@app/themes/adminlte',
                'baseUrl' => '@web/themes/adminlte',
                'pathMap' => [
                    '@app/views' => '@app/themes/adminlte',
                ],
            ],
        ],
    ],
    'params' => array_merge(
        require(__DIR__ . '/../../common/config/params.php'),
        require(__DIR__ . '/params.php')
    ),
    'as access' => [
        'class' => yii\filters\AccessControl::class,
        'except' => ['site/login'],
        'rules' => [
            ['allow' => true, 'roles' => ['@']],
        ],
    ],
];

if (YII_DEBUG) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => yii\debug\Module::class,
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.10.*'],
    ];
}

if (YII_ENV_DEV) {
    $config['modules']['gii'] = [
        'class' => yii\gii\Module::class,
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.10.*'],
        'generators' => [
            'crud' => [
                'class' => 'light\generators\crud\Generator',
            ],
            'model' => [
                'class' => 'yii\gii\generators\model\Generator',
                'templates' => [
                    'light' => '@vendor/light/yii2-generators/model/default',
                ],
            ],
            'controller' => [
                'class' => 'yii\gii\generators\controller\Generator',
                'templates' => [
                    'common' => '@common/components/generators/controller/common',
                ],
            ],
            'form' => [
                'class' => 'light\generators\form\Generator',
            ],
            'module' => [
                'class' => 'yii\gii\generators\module\Generator',
                'templates' => [
                    'light' => '@vendor/light/yii2-generators/module/common',
                ],
            ],
            'extension' => [
                'class' => 'light\generators\extension\Generator',
            ],
            'mailer' => [
                'class' => 'common\components\generators\mailer\Generator',
            ],
        ],
    ];
}

return $config;
