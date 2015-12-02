<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Webmaster */
/* @var $form ActiveForm */
?><div class="panel panel-default">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($model, 'nickname') ?>
                <?= $form->field($model, 'account') ?>
                <?= $form->field($model, 'password') ?>
                    <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
