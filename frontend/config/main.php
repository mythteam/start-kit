<?php

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => require __DIR__ . '/_modules.php',
    'language' => 'zh-CN',
    'homeUrl' => getenv('FRONTEND_URL'),
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => getenv('FRONTEND_COOKIE_VALIDATION_KEY'),
        ],
        'user' => [
            'identityClass' => 'common\models\User',
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
        'urlManager' => require __DIR__ . '/_urlManager.php',
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'appendTimestamp' => YII_ENV_DEV,
            'baseUrl' => '@cdn/assets',
            'bundles' => YII_ENV_DEV ? [] : require 'asset-bundles.php',
        ],
    ],
    'params' => array_merge(
        require(__DIR__ . '/../../common/config/params.php'),
        require(__DIR__ . '/params.php')
    ),
];
