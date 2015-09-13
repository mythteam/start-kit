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
            ],
        ],
    ],
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'params' => array_merge(
        require (__DIR__ . '/../../common/config/params.php'),
        require (__DIR__ . '/params.php')
    ),

];
