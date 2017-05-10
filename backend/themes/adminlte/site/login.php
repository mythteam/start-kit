<?php

/* @var $this \yii\web\View */
/* @var $content string */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \backend\models\LoginForm */

use yii\bootstrap\ActiveForm;

$this->title = '管理员登录';
?>
<div class="login-box">
    <!--<div class="login-logo">Please Sign In</div>-->
    <div class="login-box-body">
        <p class="login-box-msg">欢迎登录<?= Yii::$app->name ?></p>
        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
        <?= $form->field($model, 'username', [
            'template' => '{input}<span class="glyphicon glyphicon-envelope form-control-feedback"></span>{error}',
            'options' => [
                'class' => 'form-group has-feedback',
            ],
        ])->textInput(['placeholder' => '登录账号', 'autofocus' => true]) ?>
        <?= $form->field($model, 'password', [
            'template' => '{input}<span class="glyphicon glyphicon-lock form-control-feedback"></span>{error}',
            'options' => [
                'class' => 'form-group has-feedback',
            ],
        ])->passwordInput(['placeholder' => '登录密码']) ?>
        <div class="row">
            <div class="col-xs-8">
                <div class="checkbox icheck">
                    <?= $form->field($model, 'rememberMe')->checkbox() ?>
                </div>
            </div>
            <div class="col-xs-4">
                <button type="submit" class="btn btn-primary btn-block btn-flat" data-loading-text="登录中...">登入</button>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
