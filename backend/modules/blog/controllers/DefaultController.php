<?php

namespace backend\modules\blog\controllers;

use Yii;
use yii\web\Controller;

class DefaultController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            '_access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'actions' => [], //empty, apply to all actions
                        'allow' => true,
                        'roles' => ['@'],
                        'verbs' => ['GET'],
                    ],
                ],
            ],
        ];
    }

    /**
     * index action.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
