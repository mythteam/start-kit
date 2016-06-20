<?php

use common\models\Webmaster;
use yii\db\Migration;

class m160619_075016_create_webmaster extends Migration
{
    protected $tb_name = '{{%webmaster}}';

    public function up()
    {
        $this->createTable($this->tb_name, [
            'id' => $this->primaryKey(),

            'status' => $this->smallInteger(1)->notNull()->defaultValue(1),
            'is_super' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'registed_at' => $this->integer()->notNull(),
            'logged_at' => $this->integer()->notNull(),

            'auth_key' => $this->string(32)->notNull()->defaultValue(''),
            'nickname' => $this->string(50)->notNull()->defaultValue(''),
            'account' => $this->string(50)->notNull()->unique(),
            'password_hash' => $this->string(100)->notNull(),
            'password_reset_token' => $this->string(100)->unique(),
        ]);
        echo 'Init super administrator account:', PHP_EOL;
        $this->insert($this->tb_name, [
            'is_super' => WebMaster::SUPER_YES,
            'registed_at' => time(),
            'nickname' => 'Administrator',
            'account' => 'admin',
            'password_hash' => \Yii::$app->security->generatePasswordHash('admin'),
        ]);
    }

    public function down()
    {
        $this->dropTable($this->tb_name);
    }
}
