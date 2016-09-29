<?php

use common\components\grid\ChangeSingleColumn;
use common\Constants;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

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
            <?= $this->render('_search', ['model' => $searchModel]) ?>
            
        </div>
        <?php Pjax::begin(['formSelector' => '#masterSearchForm', 'options' => ['class' => 'col-md-12']]) ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'id',
                    [
                        'class' => \yii2mod\editable\EditableColumn::class,
                        'attribute' => 'nickname',
                        'label' => '姓名',
                        'url' => 'update-attr',
                        'enableSorting' => false,
                    ],
                    'account',
                    [
                        'attribute' => 'registed_at',
                        'format' => 'datetime',
                        'enableSorting' => true,
                    ],
                    'logged_at:datetime',
                    [
                        'class' => ChangeSingleColumn::class,
                        'modelAttribute' => 'status',
                        'data' => Constants::statusLabels(),
                        'handleUrl' => ['status'],
                        'header' => '状态',
                        'disable' => function ($model, $key, $index) {
                            /* @var $model \common\models\WebMaster; */
                            return $model->isSuper;
                        }
                    ],
                    [
                        'class' => ActionColumn::class,
                        'template' => '{update}'
                    ],
                ],
            ]); ?>
        <?php Pjax::end() ?>
    </div>
</div>
