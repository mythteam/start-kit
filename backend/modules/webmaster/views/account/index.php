<?php

use common\components\grid\ChangeSingleColumn;
use common\Constants;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\webmaster\models\WebmasterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '账号列表';
$this->params['breadcrumbs'][] = '系统管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box">
    <div class="box-body">
        <div class="col-md-12">
            <?= Html::a('创建管理员', ['create'], ['class' => 'btn btn-success pull-right']) ?>
        </div>
        <div class="col-md-12">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'id',
                    [
                        'attribute' => 'nickname',
                        'label' => '姓名'
                    ],
                    'account',
                    'registed_at:datetime',
                    'logged_at:datetime',
                    [
                        'class' => ChangeSingleColumn::class,
                        'modelAttribute' => 'status',
                        'data' => Constants::statusLabels(),
                        'handleUrl' => ['status'],
                        'header' => '状态',
                    ],
                    [
                        'class' => ActionColumn::class,
                        'template' => '{update}'
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
