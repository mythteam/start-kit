<?php

namespace api\rest\auth;

use yii\base\ActionFilter;
use yii\web\ForbiddenHttpException;

class SecurityAuth extends ActionFilter
{
    public $encryptAlgorithm = 'md5';

    /**
     * @inhertidoc
     */
    public function beforeAction($action)
    {

        return true;
        //throw new ForbiddenHttpException('Error Processing Request');
    }
}
