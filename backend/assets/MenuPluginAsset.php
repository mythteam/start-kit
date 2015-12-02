<?php

namespace backend\assets;

use yii\web\AssetBundle;

class MenuPluginAsset extends AssetBundle
{
    public $sourcePath = '@bower/metisMenu/dist';

    public $js = [
        'metisMenu.js',
    ];

    public $css = [
        'metisMenu.css',
    ];
}
