<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use common\widgets\Menu;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div id="wrapper">
    <!-- navbar -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <!-- band -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/"><?= Yii::$app->name ?></a>
        </div>
        <!-- right -->
        <?= Nav::widget([
            'encodeLabels' => false,
            'options' => ['class' => 'nav navbar-top-links navbar-right'],
            'items' => [
                [
                    'label' => '<i class="fa fa-user fa-fw"></i>',
                    'items' => [
                        [
                            'label' => '<i class="fa fa-user fa-fw"></i>' . t('app', 'User Profile'),
                            'url' => ['/profile/update']
                        ],
                        [
                            'label' => '<i class="fa fa-gear fa-fw"></i>' . t('app', 'Settings'),
                            'url' => ['/setting/index']
                        ],
                        '<li class="divider"></li>',
                        [
                            'label' => '<i class="fa fa-sign-out fa-fw"></i>' . t('app', 'Logout'),
                            'url' => ['/site/logout'],
                            'linkOptions' => ['data' => ['method' => 'POST']]
                        ]
                    ]
                ]
            ]
        ]); ?>
        <!-- menu -->
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                <?= Menu::widget([
                    'options' => [
                        'class' => 'nav in',
                        'id' => 'side-menu'
                    ],
                    'items' => [
                        ['label' => 'Hello,' . Yii::$app->user->identity->account, 'options' => ['class' => 'sidebar-search']],
                        [
                            'label' => t('app', 'Dashboard'),
                            'url' => ['/site/index'],
                            'icon' => 'fa-dashboard'
                        ],
                        [
                            'label' => t('app', 'Webmasters'),
                            'url' => '#',
                            'items' => [
                                ['label' => t('app', 'Accounts'), 'url' => ['/webmaster/account/list']],
                                ['label' => t('app', 'Create Account'), 'url' => ['/webmaster/account/create']]
                            ],
                            'icon' => 'fa-user'
                        ],
                        [
                            'label' => t('app', 'Settings'),
                            'url' => '#',
                            'items' => [
                                ['label' => t('app', 'Service Charge'), 'url' => ['/setting/service/charge']],
                                ['label' => t('app', 'Discount Cofiguration'), 'url' => ['/setting/discount/index']],
                                ['label' => t('app', 'Site Cofigurations'), 'url' => ['/setting/site/index']],
                            ],
                            'icon' => 'fa-cogs'
                        ],
                        [
                            'label' => t('app', 'Users'),
                            'url' => '#',
                            'items' => [
                                ['label' => t('app', 'Developers'), 'url' => ['/user/developer/index']],
                                ['label' => t('app', 'Reviewers'), 'url' => ['/user/reviewer/index']]
                            ],
                            'icon' => 'fa-users'
                        ],
                        [
                            'label' => t('app', 'Tasks'),
                            'url' => '#',
                            'items' => [
                                ['label' => t('app', 'All Tasks'), 'url' => ['/tasks/default/index']],
                            ],
                            'icon' => 'fa-tasks'
                        ],
                        [
                            'label' => t('app', 'Orders'),
                            'url' => '#',
                            'items' => [
                                ['label' => t('app', 'All Orders'), 'url' => ['/order/default/index']],
                                ['label' => t('app', 'Apps'), 'url' => ['/app/index']],
                            ],
                            'icon' => 'fa-cart-plus'
                        ],
                        [
                            'label' => t('app', 'Payment'),
                            'url' => '#',
                            'items' => [
                                ['label' => t('app', 'Withdraw Requests'), 'url' => ['/payment/withdraw/index']]
                            ],
                            'icon' => 'fa-credit-card'
                        ],
                    ]
                ]) ?>
            </div>
        </div>
    </nav>
    <!-- page -->
    <div id="page-wrapper">
        <div class="row pt15">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
        </div>
        <div class="row">
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
