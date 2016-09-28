<?php
/* @var $this yii\web\View */

$this->title = '更新管理员账号信息';
$this->params['breadcrumbs'][] = $this->title;

/** @var $model backend\models\UpdateProfileForm */
?>
<?= $this->render('_form.php', ['model' => $model]) ?>
