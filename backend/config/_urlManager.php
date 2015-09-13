<?php
return [
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'ruleConfig' => ['class' => 'yii\web\UrlRule', 'host' => getenv('BACKEND_URL')],
    // 'cache' => 'cache',
    'hostInfo' => getenv('BACKEND_URL'),
    // 'rules' => [

    // ],
];
