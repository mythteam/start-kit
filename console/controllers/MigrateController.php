<?php

namespace console\controllers;

use yii\console\controllers\MigrateController as BaseController;

class MigrateController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public $templateFile = '@app/views/db/migration.php';
    /**
     * 开发阶段指向开发目录
     * {@inheritdoc}
     */
    public $migrationPath = '@app/migrations/dev';
}
