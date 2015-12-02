<?php
/**
 * This is the template for generating an action view file.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\form\Generator */

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= $generator->modelClass ?> */
/* @var $form ActiveForm */
<?= "?>\n" ?>
<div class="panel panel-default">
    <div class="panel-heading"><?= '<?= ' ?>$this->title<?= ' ?>' ?></div>
    <div class="panel-body">
        <?= '<?php ' ?>$form = ActiveForm::begin(); ?>
        <?php foreach ($generator->getModelAttributes() as $attribute): ?>
        <?= '<?= ' ?>$form->field($model, '<?= $attribute ?>') ?>
        <?php endforeach; ?>
        <div class="form-group">
            <?= '<?= ' ?>Html::submitButton(<?= $generator->generateString('Submit') ?>, ['class' => 'btn btn-primary']) ?>
        </div>
        <?= '<?php ' ?>ActiveForm::end(); ?>
    </div>
</div>
