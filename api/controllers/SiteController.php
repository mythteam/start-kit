<?php

namespace api\controllers;

use common\traits\MethodInjectionTrait;
use Yii;
use yii\helpers\Url as url;
use yii\web\Controller;

class SiteController extends Controller
{
    use MethodInjectionTrait;
    /**
     * @inheritdoc
     */
    public $enableCsrfValidation = false;

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'doc' => [
                'class' => light\swagger\SwaggerAction::class,
                'restUrl' => url::to(['/site/api'], true),
            ],
            'api' => [
                'class' => light\swagger\SwaggerApiAction::class,
                'scanDir' => [
                    Yii::getAlias('@api/modules/v1/swagger'),
                    Yii::getAlias('@api/modules/v1/controllers'),
                    Yii::getAlias('@api/modules/v1/models'),
                    Yii::getAlias('@api/models'),
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if ('api' == $action->id) {
                app()->response->detachBehavior('beforeSend');
            }
            return true;
        }
        return false;
    }

    public function actionIndex()
    {
        return [];
    }

    public function actionCreate()
    {
        app()->response->format = \yii\web\Response::FORMAT_JSON;
        $resutl = app()->request->bodyParams;
        return ['tr' => $resutl, 'xx' => app()->request->post(), 'get' => app()->request->get()];
    }

    public function actionTest(FileNamingResolver $resolver, \Qiniu\Auth $auth)
    {
        var_dump($auth);

        // $file = new \FileNamingResolver\FileInfo('test.png');

        // var_dump($file->getPathnameRelativeTo(alias('@runtime')));

        // var_dump($resolver->resolveName($file));
        // echo $file;
    }
}
