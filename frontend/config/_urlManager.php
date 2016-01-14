<?php

return [
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'ruleConfig' => ['class' => 'yii\web\UrlRule', 'host' => env('app.FRONTEND_URL')],
    // 'cache' => 'cache',
    'hostInfo' => env('app.FRONTEND_URL'),
    // 'rules' => [

    // ],
];
