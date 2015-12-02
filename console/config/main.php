<?php

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii'],
    'controllerNamespace' => 'console\controllers',
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'logFile' => '@runtime/logs/db.log',
                    'logVars' => [],
                    'categories' => ['yii\db*'],
                    'enabled' => YII_DEBUG,
                ],
            ],
        ],
    ],
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'params' => array_merge(
        require(__DIR__ . '/../../common/config/params.php'),
        require(__DIR__ . '/params.php')
    ),
    'controllerMap' => [
        'migrate' => 'console\controllers\MigrateController',
    ],
];
