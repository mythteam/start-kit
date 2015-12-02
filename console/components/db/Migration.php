<?php

namespace console\components\db;

use yii\db\Migration as BaseMigration;

class Migration extends BaseMigration
{
    /**
     * 创建表的选项.
     *
     * @var string
     */
    protected $tableOptions = null;

    /**
     * 表是否使用事物,默认使用.
     *
     * @var bool
     */
    protected $useTransaction = true;

    public function init()
    {
        // $this->db = 'db2';
        parent::init();

        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $this->tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=' . ($this->useTransaction ?
                'InnoDB' : 'MyISAM') . ' AUTO_INCREMENT=10000';
            //MyISAM vs InnoDB
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createTable($table, $columns, $tableOptions = null)
    {
        parent::createTable($table, $columns, $tableOptions ?: $this->tableOptions);
    }
}
