<?php

namespace backend\controllers;

use backend\models\UpdateProfileForm;
use common\traits\MethodInjectionTrait;

/**
 *
 */
class ProfileController extends \yii\web\Controller
{
    use MethodInjectionTrait;

    /**
     * @param UpdateProfileForm $model
     *
     * @return mixed
     */
    public function actionUpdate(UpdateProfileForm $model)
    {
        return $this->render('update', [
            'model' => $model,
        ]);
    }
}
