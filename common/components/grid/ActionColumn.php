<?php

namespace common\components\grid;

use yii\grid\Column;

class ActionColumn extends Column
{
    /**
     * {@inheritdoc}
     */
    public $headerOptions = ['class' => 'action-column'];
    /**
     * @var array
     */
    public $buttons = [];
    /**
     * @var array
     */
    public $visibleButtons = [];

    /**
     * {@inheritdoc}
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $html = '';
        foreach ($this->buttons as $key => $button) {
            if (isset($this->visibleButtons[$key])) {
                $isVisible = $this->visibleButtons[$key] instanceof \Closure
                    ? call_user_func($this->visibleButtons[$key], $model, $key, $index)
                    : $this->visibleButtons[$key];
            } else {
                $isVisible = true;
            }

            if (!$isVisible) {
                continue;
            }

            $html .= '&nbsp;' . call_user_func($button, $model, $key, $index);
        }

        return $html;
    }
}
