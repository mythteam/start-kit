<?php

namespace common\finder;

use yii\base\Object;
use yii\web\NotFoundHttpException;

/**
 * The abstract class of all the finders.
 */
abstract class Finder extends Object implements FinderInterface
{
    /**
     * @var string the Model Class
     */
    public $modelClass;

    /**
     * {@inheritdoc}
     */
    public function getQuery()
    {
        /** @var \yii\db\ActiveRecord $class */
        $class = $this->modelClass;

        return $class::find();
    }

    /**
     * {@inheritdoc}
     */
    public function one($id)
    {
        $id = is_array($id) ? $id : ['id' => $id];
        $model = $this->getQuery()->where($id)->limit(1)->one();

        if (null === $model) {
            throw new NotFoundHttpException('Model is not found');
        }

        return $model;
    }
}
