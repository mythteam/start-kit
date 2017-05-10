<?php

namespace common\widgets\ue;

use yii\web\AssetBundle;

final class UEditorAsset extends AssetBundle
{
    /**
     * {@inheritdoc}
     */
    public $js = [
        'ueditor.config.js',
        'ueditor.all.js',
    ];
    /**
     * {@inheritdoc}
     */
    public $depends = ['yii\web\JqueryAsset'];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->sourcePath = '@bower/ueditor';
        parent::init();
    }
}
