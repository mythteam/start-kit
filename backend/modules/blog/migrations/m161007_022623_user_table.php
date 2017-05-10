<?php

use console\components\db\Migration;

class m161007_022623_user_table extends Migration
{
    protected $tb_name = '{{%user}}';

    public function safeUp()
    {
        $this->dropTableIfExists($this->tb_name);
        $this->createTable($this->tb_name, [
            'id' => $this->primaryKey(),
            'status' => $this->smallInteger(1)->notNull()->defaultValue(0)->comment('用户状态0. 禁用 1.启用'),
            'register_at' => $this->integer()->notNull(),
            'slug' => $this->string(50)->notNull()->unique()->comment('个性后缀'),
            'username' => $this->string(100)->notNull()->comment('用户姓名'),
            'auth_key' => $this->string(32)->notNull()->defaultValue(''),
            'password_hash' => $this->string()->notNull(),
            'access_token' => $this->string(32)->notNull()->comment('APP登录token'),
            'password_reset_token' => $this->string()->unique(),
        ]);
    }

    public function down()
    {
        $this->dropTable($this->tb_name);
    }
}
