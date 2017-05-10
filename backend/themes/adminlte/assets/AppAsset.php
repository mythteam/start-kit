<?php

namespace backend\themes\adminlte\assets;

use common\assets\PaceAsset;
use light\widgets\LockBsFormAsset;
use light\widgets\SweetSubmitAsset;
use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/themes/adminlte';

    public $css = [
        'css/site.css',
    ];

    public $depends = [
        AdminLTEAsset::class,
        SweetSubmitAsset::class,
        LockBsFormAsset::class,
        PaceAsset::class,
    ];
}
