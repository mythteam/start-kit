<?php

$config = [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => [
        'log',
        api\components\Setup::class,
    ],
    'modules' => require '_modules.php',
    'language' => 'zh-CN',
    'components' => [
        'request' => [
            'enableCsrfValidation' => false,
            'enableCookieValidation' => false,
            'parsers' => [
                'application/json' => yii\web\JsonParser::class,
            ],
        ],
        'response' => [
            'as beforeSend' => api\components\behaviors\FormatResponseBehavior::class,
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
            'loginUrl' => null,
            'enableSession' => false,
        ],
        'cache' => [
            'keyPrefix' => 'api',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            // 'suffix' => '.json',
            'ruleConfig' => [
                'class' => yii\rest\UrlRule::class,
                // 'prefix' => 'v1',
            ],
            'hostInfo' => API_URL,
            'rules' => require '_urls.php',
        ],
        'errorHandler' => [
            'class' => api\components\ErrorHandler::class,
        ],
        'log' => [
            'targets' => [
                [
                    'class' => yii\log\FileTarget::class,
                    'levels' => ['trace'],
                    'categories' => ['debug', 'debug.*'],
                    'logFile' => '@runtime/logs/debug.log',
                ],
                //for performance tracking.
                //[
                //    'class' => 'yii\log\FileTarget',
                //    'levels' => ['profile'],
                //    'logVars' => [],
                //    'categories' => ['pf.*'],
                //    'logFile' => '@runtime/logs/profile.log',
                //],
            ],
        ],
        'i18n' => [
            'translations' => [
                'notice*' => [
                    'class' => yii\i18n\PhpMessageSource::class,
                    'basePath' => '@app/messages', //app下的messages文件夹
                ],
            ],
        ],
    ],
    'params' => array_merge(
        require(__DIR__ . '/../../common/config/params.php'),
        require(__DIR__ . '/params.php')
    ),
];
if (YII_DEBUG) {
    $config['bootstrap'][] = 'debug';
    //do not show debug bar.
    //$config['modules']['debug'] = [
    //    'class' => yii\debug\Module::class,
    //    'allowedIPs' => ['127.0.0.1', '::1', '192.168.10.*'],
    //];
}

if (YII_ENV_DEV) {
    $config['modules']['gii'] = [
        'class' => yii\gii\Module::class,
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

