<?php

namespace backend\themes\adminlte\assets;

use common\assets\FontAwesomeAsset;
use yii\bootstrap\BootstrapAsset;
use yii\bootstrap\BootstrapPluginAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Class AdminLTEAsset
 *
 * @see https://almsaeedstudio.com/preview
 */
class AdminLTEAsset extends AssetBundle
{
    public $sourcePath = '@bower/AdminLTE/dist';

    public $js = [
        'js/app.js'
    ];

    public $css = [
        'css/AdminLTE.css',
        'css/skins/skin-blue.css'
    ];

    public $depends = [
        BootstrapAsset::class,
        JqueryAsset::class,
        BootstrapPluginAsset::class,
        FontAwesomeAsset::class,
    ];
}
