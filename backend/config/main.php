<?php

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => require '_modules.php',
    'language' => 'zh-CN',
    'homeUrl' => getenv('BACKEND_URL'),
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => getenv('BACKEND_COOKIE_VALIDATION_KEY'),
        ],
        'user' => [
            'identityClass' => 'common\models\Webmaster',
            'enableAutoLogin' => true,
            'as afterLogin' => 'backend\components\behaviors\AfterLoginBehavior',
            'authTimeout' => 3600, //auto logout 60 mins
        ],
        'cache' => [
            'keyPrefix' => 'frontend',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => require '_urlManager.php',
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'appendTimestamp' => !YII_ENV_PROD,
            'bundles' => YII_ENV_PROD ? require 'asset-bundles.php' : [],
        ],
    ],
    'params' => array_merge(
        require (__DIR__ . '/../../common/config/params.php'),
        require (__DIR__ . '/params.php')
    ),
    'as access' => [
        'class' => 'yii\filters\AccessControl',
        'except' => ['site/login'],
        'rules' => [
            ['allow' => true, 'roles' => ['@']],
        ],
    ],
];
