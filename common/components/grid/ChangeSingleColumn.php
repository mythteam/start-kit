<?php

namespace common\components\grid;

use yii\base\InvalidConfigException;
use yii\bootstrap\ButtonDropdown;
use yii\grid\Column;

class ChangeSingleColumn extends Column
{
    /**
     * @var array The data for the column. This is the range of the column to change to.
     */
    public $data;
    /**
     * @var string The target model attribute.
     */
    public $modelAttribute;
    /**
     * @var string The confirm message.
     */
    public $confirmMessage;
    /**
     * @var string The handler method url.
     */
    public $handleUrl;
    /**
     * @var array|callable
     */
    public $buttonOptions = ['class' => 'btn btn-default btn-sm'];
    /**
     * @var bool|callable If the button disabled.
     */
    public $disable = false;
    
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        if (null === $this->modelAttribute) {
            throw new InvalidConfigException('modelAttribute must be set');
        }
        if (null === $this->confirmMessage) {
            $this->confirmMessage = '确定更改状态?';
        }
    }
    /**
     * {@inheritdoc}
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $items = [];
        $selected_label = '';
        if (is_callable($this->buttonOptions)) {
            $buttonOptions = call_user_func($this->buttonOptions, $model);
        } else {
            $buttonOptions = $this->buttonOptions;
        }
        
        foreach ($this->data as $data_key => $label) {
            $_item = ['label' => $label];
            if ($model->{$this->modelAttribute} == $data_key) {
                $_item['options'] = ['class' => 'disabled'];
                $selected_label = $label;
            } else {
                $_item['linkOptions'] = [
                    'data' => [
                        'confirm' => $this->confirmMessage,
                        'method' => 'post',
                        'pjax' => 0,
                        'params' => [
                            'id' => $model->id,
                            $this->modelAttribute => $data_key,
                        ],
                    ],
                ];
                $_item['url'] = $this->handleUrl;
            }
            $items[] = $_item;
        }
        
        $disableButton = $this->disable;
        if (is_callable($this->disable)) {
            $disableButton =  call_user_func($this->disable, $model, $key, $index);
        }
        
        if (true === $disableButton) {
            $buttonOptions = ['disabled' => 'disabled'];
        }

        return ButtonDropdown::widget([
            'label' => $selected_label,
            'options' => $buttonOptions,
            'dropdown' => [
                'items' => $items,
            ],
        ]);
    }
}
