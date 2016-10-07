<?php
use console\components\db\Migration;
use yii\helpers\Console;

class m161007_023218_articles_table extends Migration
{
    protected $tb_name = 'table';

    public function safeUp()
    {
        $this->createTable($this->tb_name, [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
            'tag_id' => $this->integer()->notNull()->comment('所属标签'),
            'views' => $this->integer()->notNull()->defaultValue(0)->comment('浏览量'),
            'title' => $this->string()->notNull()->comment('标题'),
            'meta_keyword' => $this->string()->notNull()->defaultValue(''),
            'meta_description' => $this->string(500)->notNull()->defaultValue(''),
            'brief' => $this->string(500)->notNull()->defaultValue('')->comment('摘要'),
            'content' => $this->text()->comment('内容'),
        ]);
        
        $this->createIndex('idx_tag', $this->tb_name, ['tag_id']);
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
