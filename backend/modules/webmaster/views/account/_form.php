<?php

use common\Constants;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Webmaster */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="col-md-9 col-md-offset-1">
    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal'
    ]); ?>
    <?= $form->field($model, 'nickname')->textInput(['maxlength' => true, 'placeholder' => '姓名'])->label('姓名') ?>
    <?= $form->field($model, 'account')->textInput([
        'maxlength' => true,
        'placeholder' => '登录账号',
        'disabled' => !$model->isNewRecord,
    ])->label('账号') ?>
    
    <?= $form->field($model, 'password')->passwordInput(['placeholder' => '登录密码'])->label('密码') ?>
    <?= $form->field($model, 'status', ['inline' => true])->radioList(Constants::statusLabels()) ?>
    <div class="form-group">
        <div class="col-sm-6 col-sm-offset-3">
            <?= Html::submitButton(
                $model->isNewRecord ? '创建' : '更新',
                [
                    'class' => $model->isNewRecord ? 'btn btn-success btn-lg btn-block' : 'btn btn-primary btn-lg btn-block',
                    'data-loading-text' => '保存中...'
                ]
            ) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
