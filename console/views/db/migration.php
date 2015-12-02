<?php
/**
 * This view is used by console/controllers/MigrateController.php
 * The following variables are available in this view:
 */
/* @var $className string the new migration class name */
echo "<?php\n";
?>
use console\components\db\Migration;
use yii\helpers\Console;

class <?= $className ?> extends Migration
{
    protected $tb_name = 'table';

    public function up()
    {
        $this->createTable($this->tb_name, [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
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
