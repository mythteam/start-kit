<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['/security/reset-password', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p><?= __('email', 'Hi,my dear customer') ?>,</p>

    <p><?= __('email', 'Follow the link below to reset your password:') ?></p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
