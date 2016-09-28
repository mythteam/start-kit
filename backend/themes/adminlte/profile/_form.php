<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Webmaster */
/* @var $form ActiveForm */
?><div class="panel panel-default">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?= error_summary($model) ?>
        <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'account')->textInput(['readonly' => true]) ?>
            <?= $form->field($model, 'nickname') ?>
            <?= $form->field($model, 'password') ?>
            <div class="form-group">
                <?= Html::submitButton('提交', ['class' => 'btn btn-primary', 'data-loading-text' => '保存中']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
