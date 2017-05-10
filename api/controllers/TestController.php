<?php

namespace api\controllers;

use yii\web\Controller;

class TestController extends Controller
{
    public function actionIndex()
    {
        echo 'success';
    }

    public function actionCreate()
    {
        $result = app()->request->bodyParams;

        return [
            'body' => $result,
            'post' => app()->request->post(),
            'get' => app()->request->get(),
        ];
    }
}
