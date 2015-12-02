<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\webmaster\models\WebmasterSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="webmaster-search">

    <?php $form = ActiveForm::begin([
        'action' => ['list'],
        'method' => 'get',
        'options' => ['class' => 'form-inline mb15'],
        'fieldConfig' => ['template' => '{input}']
    ]); ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'is_super') ?>

    <?= $form->field($model, 'registed_at') ?>

    <?php // echo $form->field($model, 'account') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?= Html::a(Yii::t('app', 'Create Webmaster'), ['create'], ['class' => 'btn btn-success pull-right']) ?>
    <?php ActiveForm::end(); ?>
</div>
