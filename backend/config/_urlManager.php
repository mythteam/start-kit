<?php

return [
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'ruleConfig' => ['class' => 'yii\web\UrlRule', 'host' => env('app.BACKEND_URL')],
    // 'cache' => 'cache',
    'hostInfo' => env('app.BACKEND_URL'),
    // 'rules' => [

    // ],
];
