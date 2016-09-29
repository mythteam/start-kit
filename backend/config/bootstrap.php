<?php

defined('YII_DEBUG') or define('YII_DEBUG', Yaconf::get('welfare.backend.debug', 'false') === 'true');
defined('YII_ENV') or define('YII_ENV', Yaconf::get('welfare.backend.env', 'prod'));

\Yii::$container->set(yii\widgets\ActiveForm::class, [
    'errorSummaryCssClass' => 'alert alert-danger',
]);
\Yii::$container->set(yii\bootstrap\ActiveForm::class, [
    'errorSummaryCssClass' => 'alert alert-danger',
]);
\Yii::$container->set('yii\grid\DataColumn', [
    'enableSorting' => false,
]);
Yii::$container->set(\yii\widgets\Pjax::class, [
    'timeout' => 5000,
    'formSelector' => false,
]);
//DateRangePicker
Yii::$container->set(\kartik\daterange\DateRangePicker::class, [
    'pluginOptions' => [
        'locale' => [
            'format' => 'YYYY-MM-DD'
        ],
    ]
]);

Yii::$container->set(\common\widgets\ue\UEditor::class, [
    'jsOptions' => [
        'serverUrl' => '/upload/image',
        'toolbars' => [
            [
                'fullscreen', 'source', 'undo', 'redo', '|',
                'bold', 'italic', 'underline', 'fontborder', 'strikethrough', '|',
                'insertimage',
                'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote',
                'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist',
                'insertunorderedlist', 'selectall', 'cleardoc'
            ],
        ],
        'initialFrameHeight' => 500,
        'elementPathEnabled' => false,
        'wordCount' => false,
    ]
]);
