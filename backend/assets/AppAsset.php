<?php

namespace backend\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/themes/sbadmin';
    public $css = [
        'css/site.css',
    ];
    public $js = [
        'js/boot.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'backend\assets\MenuPluginAsset',
        'backend\assets\SbAdminAsset',
        'light\widgets\SweetSubmitAsset',
        'light\widgets\LockBsFormAsset',
    ];
}
