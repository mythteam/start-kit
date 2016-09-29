<?php

use console\components\db\Migration;
use yii\helpers\Console;

class m130524_201442_user_table extends Migration
{
    protected $tb_name = '{{%user}}';

    public function safeUp()
    {
        if (!Console::confirm('Are you sure to init user table?')) {
            return;
        }
        
        $this->dropTableIfExists($this->tb_name);
        $this->createTable($this->tb_name, [
            'id' => $this->primaryKey(),
            'status' => $this->smallInteger(1)->notNull()->defaultValue(0)->comment('用户状态0. 禁用 1.启用'),
            'identity' => $this->smallInteger(1)->notNull(),
            'is_online' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'register_at' => $this->integer()->notNull(),
            'phone' => $this->string(11)->notNull()->unique()->comment('手机号'),
            'username' => $this->string(100)->notNull()->comment('用户姓名'),
            'auth_key' => $this->string(32)->notNull()->defaultValue(''),
            'password_hash' => $this->string()->notNull(),
            'access_token' => $this->string(32)->notNull()->comment('APP登录token'),
            'password_reset_token' => $this->string()->unique(),
        ]);
    
        $this->createIndex('idx_phone', $this->tb, 'phone');
    }

    public function down()
    {
        $this->dropTable($this->tb_name);
    }
}
