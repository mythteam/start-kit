<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Webmaster */

$this->title = '更新账号';
$this->params['breadcrumbs'][] = '系统管理';
$this->params['breadcrumbs'][] = ['label' => '账号列表', 'url' => ['list']];
$this->params['breadcrumbs'][] = '更新账号';
?>
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-body">
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</div>
