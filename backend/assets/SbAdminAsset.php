<?php

namespace backend\assets;

use yii\web\AssetBundle;

class SbAdminAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [];

    public $css = [
        'css/sb-admin-2.css',
    ];

    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'common\assets\FontAwesomeAsset',
    ];
}
