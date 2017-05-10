<?php

$config = [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'db' => [
            'class' => yii\db\Connection::class,
            'dsn' => Yaconf::get('kit.db.dsn'),
            'username' => Yaconf::get('kit.db.username'),
            'password' => Yaconf::get('kit.db.password'),
            'tablePrefix' => Yaconf::get('kit.db.table_prefix', ''),
            'enableSchemaCache' => YII_DEBUG,
            'charset' => 'utf8',
            'schemaCache' => 'rcache',
        ],
        'mailer' => [
            'class' => yii\swiftmailer\Mailer::class,
            'viewPath' => '@common/business/mail/views',
            'htmlLayout' => '@common/business/mail/views/layouts/main.php',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => YII_DEBUG,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => Yaconf::get('kit.email.host'),
                'username' => Yaconf::get('kit.email.username'),
                'password' => Yaconf::get('kit.email.password'),
                'port' => Yaconf::get('kit.email.port', 25),
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
                    'class' => common\components\log\FileTarget::class,
                    'levels' => ['warning'],
                    'logFile' => '@runtime/logs/warning_{date}.log',
                ],
                [
                    'class' => common\components\log\FileTarget::class,
                    'levels' => ['error'],
                    'except' => ['yii\web\HttpException:404'],
                    'logFile' => '@runtime/logs/error_{date}.log',
                ],
                [
                    'class' => yii\log\EmailTarget::class,
                    'levels' => ['error'],
                    'enabled' => Yaconf::get('kit.logging.email', 'false') === 'true',
                    'categories' => [
                        'yii\db\*',
                        'yii\base\ErrorException*',
                        'yii\base\UnknownMethodException*',
                        'yii\base\UnknownClassException*',
                        'yii\base\UnknownPropertyException*',
                        'yii\base\InvalidValueException*',
                    ],
                    'message' => [
                        'from' => [Yaconf::get('kit.email.admin')],
                        'to' => [Yaconf::get('kit.email.enginer')],
                        'subject' => 'Error occured, Please attention!',
                    ],
                ],
            ],
        ],
        'cache' => [
            'class' => YII_DEBUG ? yii\caching\DummyCache::class : yii\caching\FileCache::class,
        ],
        'redis' => [
            'class' => yii\redis\Connection::class,
            'hostname' => Yaconf::get('kit.redis.host'),
            'port' => Yaconf::get('kit.redis.port', 6379),
            'password' => Yaconf::get('kit.redis.pass'),
        ],
        'rcache' => [
            'class' => yii\redis\Cache::class,
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => yii\i18n\PhpMessageSource::class,
                    'basePath' => '@app/messages', //app下的messages文件夹
                ],
                '*' => [
                    'class' => yii\i18n\PhpMessageSource::class,
                    'basePath' => '@common/messages',
                ],
            ],
        ],
    ],
];

return $config;
