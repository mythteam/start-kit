<?php
$config = [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => getenv('DB_DSN'),
            'username' => getenv('DB_USERNAME'),
            'password' => getenv('DB_PASSWORD'),
            'tablePrefix' => getenv('DB_TABLE_PREFIX'),
            'enableSchemaCache' => YII_DEBUG,
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'htmlLayout' => '@common/mail/layouts/transactional.php',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => YII_DEBUG,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => getenv('SMTP_HOST'),
                'username' => getenv('SMTP_USERNAME'),
                'password' => getenv('SMTP_PASSWORD'),
                'port' => getenv('SMTP_PORT') ?: '25',
            ],
        ],
        'formatter' => [
            'currencyCode' => 'RMB',
            'dateFormat' => 'yyyy-MM-dd',
            'datetimeFormat' => 'yyyy-MM-dd HH:mm',
            'timeFormat' => 'HH:mm:ss',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
        ],
        'log' => [
            'traceLevel' => 0,
            'targets' => [
                [
                    'class' => 'common\components\log\FileTarget',
                    'levels' => ['warning'],
                    'logFile' => '@runtime/logs/warning_{date}.log',
                ],
                [
                    'class' => 'common\components\log\FileTarget',
                    'levels' => ['error'],
                    'except' => ['yii\web\HttpException:404'],
                    'logFile' => '@runtime/logs/error_{date}.log',
                ],
                [
                    'class' => 'yii\log\EmailTarget',
                    'levels' => ['error'],
                    'enabled' => !YII_DEBUG,
                    'categories' => [
                        'yii\db\*',
                        'yii\base\ErrorException*',
                        'yii\base\UnknownMethodException*',
                        'yii\base\UnknownClassException*',
                        'yii\base\UnknownPropertyException*',
                        'yii\base\InvalidValueException*',
                    ],
                    'message' => [
                       'from' => [getenv('ADMIN_EMAIL')],
                       'to' => [getenv('ENGINEER_EMAIL')],
                       'subject' => 'Error occured, Please attention!',
                    ],
                ],
            ],
        ],
        'cache' => [
            'class' => YII_DEBUG ? 'yii\caching\DummyCache' : 'yii\caching\FileCache',
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages', //app下的messages文件夹
                ],
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
            ],
        ],
    ],
];

if (YII_DEBUG) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];
}

if (YII_ENV_DEV) {
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
