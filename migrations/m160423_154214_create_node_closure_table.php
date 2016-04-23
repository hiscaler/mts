<?php

use yii\db\Migration;

class m160423_154214_create_node_closure_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%node_closure}}', [
            'parent_id' => $this->integer()->notNull(),
            'child_id' => $this->integer()->notNull(),
            'level' => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%node_closure}}');
    }

}
