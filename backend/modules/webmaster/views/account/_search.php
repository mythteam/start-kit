<?php

use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\webmaster\models\WebmasterSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="webmaster-search">
    <?php $form = ActiveForm::begin([
        'id' => 'masterSearchForm',
        'action' => ['list'],
        'method' => 'get',
        'options' => ['class' => 'form-inline mb15'],
        'fieldConfig' => ['template' => '{input}'],
        'enableClientScript' => false
    ]); ?>
    <?= $form->field($model, 'status')->dropDownList(['' => '账号状态'] + \common\Constants::statusLabels()) ?>
    <?= $form->field($model, 'register_at')->widget(DateRangePicker::class, [
        'options' => [
            'placeholder' => '创建时间区间',
            'class' => 'form-control'
        ]
    ]) ?>
    <?= $form->field($model, 'query')->textInput(['placeholder' => '搜索查询条件']) ?>
    <div class="form-group">
        <?= Html::submitButton('查询', ['class' => 'btn btn-primary', 'data-loading-text' => '查询中']) ?>
    </div>
    <?= Html::a('创建管理员', ['create'], ['class' => 'btn btn-info pull-right']) ?>
    <?php ActiveForm::end(); ?>
</div>
