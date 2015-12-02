<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Webmaster */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Webmaster',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Webmasters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<?= $this->render('_form', [
    'model' => $model,
]) ?>
