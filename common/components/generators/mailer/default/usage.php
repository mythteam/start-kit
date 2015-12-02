<?php
/**
 * This is the template for generating an action view file.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\form\Generator */

echo "<?php\n";
?>
$mailer = new <?= $generator->mailerName ?>([
    'id' => $id
]);
$mailer->enqueue();
