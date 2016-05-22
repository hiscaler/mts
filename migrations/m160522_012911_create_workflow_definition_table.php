<?php

use yii\db\Migration;

/**
 * Handles the creation for table `workflow_definition_table`.
 */
class m160522_012911_create_workflow_definition_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('workflow_definition_table', [
            'id' => $this->primaryKey(),
            'rule_id' => $this->integer()->notNull(),
            'ordering' => $this->smallInteger()->notNull()->defaultValue(0),
            'user_id' => $this->integer()->notNull(),
            'user_group_id' => $this->integer()->notNull()->defaultValue(0),
            'enabled' => $this->boolean()->notNull()->defaultValue(1),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('workflow_definition_table');
    }

}
