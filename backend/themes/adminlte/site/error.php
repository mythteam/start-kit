<?php

/** @var \yii\base\Exception $exception */
$this->title = $name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="error-page">
    <div class="error-content">
        <h3><i class="fa fa-warning text-red"></i> <?= $message ?></h3>
        <p>&nbsp;</p>
    </div>
</div>
