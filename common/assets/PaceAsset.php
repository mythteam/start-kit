<?php

namespace common\assets;

use yii\web\AssetBundle;

/**
 * Class PaceAsset.
 *
 * Pace asset bundle
 *
 * @see https://github.com/HubSpot/pace v1.0.2
 */
class PaceAsset extends AssetBundle
{
    public $sourcePath = '@bower/PACE';

    public $js = [
        'pace.js',
    ];

    public $css = [
        'themes/white/pace-theme-flash.css',
    ];
}
