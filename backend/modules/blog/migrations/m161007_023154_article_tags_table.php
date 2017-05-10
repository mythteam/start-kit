<?php

use console\components\db\Migration;
use yii\helpers\Console;

class m161007_023154_article_tags_table extends Migration
{
    protected $tb_name = '{{%article_tags}}';

    public function up()
    {
        $this->dropTableIfExists($this->tb_name);
        $this->createTable($this->tb_name, [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
            'article_count' => $this->integer()->notNull()->defaultValue(0)->comment('文章数'),
            'slug' => $this->string(50)->notNull()->unique()->comment('标签URL后缀'),
            'name' => $this->string(100)->notNull()->comment('标签名称'),
        ]);
    }

    public function down()
    {
        $confirm = Console::confirm("Do you want drop tables:{$this->tb_name}?");
        if ($confirm) {
            $this->dropTable($this->tb_name);
            Console::output('Drop tables done!');
        } else {
            Console::output('Canceled!');
        }
    }
}
