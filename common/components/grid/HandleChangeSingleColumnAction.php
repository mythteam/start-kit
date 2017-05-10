<?php

namespace common\components\grid;

use Yii;
use yii\base\Action;
use yii\helpers\Html;
use yii\web\MethodNotAllowedHttpException;

/**
 * 通用修改ActiveRecord某一属性的Action,.
 *
 * 更新成功返回 [[index]]
 *
 * [[注意：]] 目标Controller的 [[findModel]] 必须为公有方法
 *
 * @version 1.0.0
 */
class HandleChangeSingleColumnAction extends Action
{
    /**
     * @var string 修改的目标属性, 默认值是 [[status]]
     */
    public $changeProperty = 'status';
    /**
     * @var string 获取提交值得属性, 默认是 [[status]], 默认从 [[status]] 字段获取更新值
     */
    public $getQueryParam = 'status';
    /**
     * @var string the hook for the model operation, we can define more via this
     */
    public $modelHandleHook;
    /**
     * @var string|array 操作之后的跳转地址
     */
    public $redirect;
    /**
     * @var array Update to attributes
     */
    public $updateAttributes;
    /**
     * @var callable Find model method
     */
    public $findModelMethod;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        if (!Yii::$app->getRequest()->getIsPost()) {
            throw new MethodNotAllowedHttpException('only post method is allowed.');
        }
        $this->findModelMethod = [$this->controller, 'findModel'];
        if (null === $this->updateAttributes) {
            $this->updateAttributes = [$this->changeProperty];
        }
        if (null === $this->redirect) {
            $this->redirect = Yii::$app->getRequest()->getReferrer();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $model = call_user_func($this->findModelMethod, Yii::$app->getRequest()->post('id'));
        //$model = $this->controller->findModel(Yii::$app->getRequest()->post('id'));
        if (is_callable($this->modelHandleHook)) {
            call_user_func($this->modelHandleHook, $model);
        }
        $model->{$this->changeProperty} = Yii::$app->getRequest()->post($this->changeProperty);
        if (false === $model->update(true, $this->updateAttributes)) {
            Yii::$app->session->setFlash('error', Html::errorSummary($model));
        } else {
            Yii::$app->session->setFlash('success', '更新成功');
        }

        return $this->controller->redirect($this->redirect ?: ['index']);
    }
}
