<?php

namespace common\widgets\ue;

use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;
use yii\widgets\InputWidget;

class UEditor extends InputWidget
{
    /**
     * @var string UE初始化的container dom ID
     */
    public $id;
    /**
     * @var string 初始化默认值
     */
    public $value;
    /**
     * @var string 表单字段名
     */
    public $name;
    /**
     * @var string Tag/ScriptTag HtmlStyle
     */
    public $style;
    /**
     * @var bool 是否渲染Tag
     */
    public $renderTag = true;
    /**
     * @var array UEditor 传入参数
     */
    public $jsOptions = [];

    /**
     * @var string
     */
    public $readyEvent;

    /**
     * {@inheritdoc}
     */
    final public function init()
    {
        parent::init();

        if (empty($this->id)) {
            $this->id = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->getId();
        }
        if (empty($this->name)) {
            $this->name = $this->hasModel() ? Html::getInputName($this->model, $this->attribute) : $this->id;
        }
        //尝试在Model中获取属性的默认值
        $attributeName = $this->attribute;
        if (empty($this->value) && $this->hasModel()) {
            $this->value = $this->model->{$attributeName};
        }
    }

    /**
     * {@inheritdoc}
     */
    final public function run()
    {
        UEditorAsset::register($this->getView());
        $this->registerScripts();

        if ($this->renderTag) {
            echo $this->renderTag();
        }
    }

    /**
     * Register scripts.
     */
    private function registerScripts()
    {
        $options = Json::encode($this->jsOptions);
        $script = "UE.getEditor('{$this->id}', {$options})";

        if ($this->readyEvent) {
            $script .= ".ready(function() {{$this->readyEvent}})";
        }
        $script .= ';';
        $this->getView()->registerJs($script, View::POS_READY);
    }

    /**
     * @return string
     */
    private function renderTag()
    {
        $id = $this->id;
        $content = $this->value;
        $name = $this->name;

        $style = $this->style ? " style=\"$this->style\"" : '';

        return <<<EOF
<script id="{$id}" name="{$name}" $style type="text/plain">{$content}</script>
EOF;
    }
}
