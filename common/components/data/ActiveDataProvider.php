<?php

namespace common\components\data;

use yii\data\ActiveDataProvider as BaseProvider;

class ActiveDataProvider extends BaseProvider
{
    /**
     * {@inheritdoc}
     */
    protected function prepareModels()
    {
        if (false !== $this->getPagination() &&
            0 === $this->getTotalCount()) {
            return [];
        }

        return parent::prepareModels();
    }
}
