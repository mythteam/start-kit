<?php
/* @var $this yii\web\View */

$this->title = t('app', 'Profile Update');
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('_form.php', ['model' => $model]) ?>
