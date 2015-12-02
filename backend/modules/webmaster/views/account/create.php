<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Webmaster */

$this->title = Yii::t('app', 'Create Webmaster');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Webmasters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('_form', [
    'model' => $model,
]) ?>
