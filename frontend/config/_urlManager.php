<?php
return [
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'ruleConfig' => ['class' => 'yii\web\UrlRule', 'host' => getenv('FRONTEND_URL')],
    // 'cache' => 'cache',
    'hostInfo' => getenv('FRONTEND_URL'),
    // 'rules' => [

    // ],
];
