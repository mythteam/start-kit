<?php

namespace common\traits;

use Yii;
use yii\base\Model;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Perform an ajax validation.
 */
trait AjaxValidationTrait
{
    /**
     * Performs the ajax validation.
     *
     * @param Model $model
     *
     * @return mixed
     */
    protected function performAjaxValidation(Model $model)
    {
        if (Yii::$app->getRequest()->isAjax
            && $model->load(Yii::$app->getRequest()->post())) {
            Yii::$app->getResponse()->format = Response::FORMAT_JSON;
            /*ActiveForm::validate*/
            echo json_encode(ActiveForm::validate($model));
            Yii::$app->end();//terminate the application
        }
    }
}
