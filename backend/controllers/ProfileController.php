<?php

namespace backend\controllers;

use backend\models\UpdateProfileForm;
use common\traits\MethodInjectionTrait;
use Yii;
use yii\web\Controller;

class ProfileController extends Controller
{
    use MethodInjectionTrait;

    /**
     * @param UpdateProfileForm $model
     *
     * @return mixed
     */
    public function actionUpdate(UpdateProfileForm $model): string
    {
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            if ($model->submit()) {
                Yii::$app->session->setFlash('success', '保存成功');
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
}
