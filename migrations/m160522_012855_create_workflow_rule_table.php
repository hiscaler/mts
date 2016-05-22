<?php

use yii\db\Migration;

/**
 * Handles the creation for table `workflow_rule_table`.
 */
class m160522_012855_create_workflow_rule_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%workflow_rule}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull(),
            'description' => $this->text()->notNull(),
            'enabled' => $this->boolean()->notNull()->defaultValue(1),
            'tenant_id' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%workflow_rule}}');
    }

}
