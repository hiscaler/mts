<?php

use yii\db\Migration;

class m160423_154156_create_node_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%node}}', [
            'id' => $this->primaryKey(),
            'alias' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'model_name' => $this->string(60)->notNull(),
            'parameters' => $this->string()->notNull(),
            'parent_id' => $this->integer()->notNull()->defaultValue(0),
            'parent_ids' => $this->string(),
            'parent_names' => $this->string(),
            'level' => $this->smallInteger()->notNull()->defaultValue(0),
            'ordering' => $this->integer()->notNull()->defaultValue(0),
            'direct_data_count' => $this->integer()->notNull()->defaultValue(0),
            'relation_data_count' => $this->integer()->notNull()->defaultValue(0),
            'enabled' => $this->boolean()->notNull()->defaultValue(1),
            'entity_status' => $this->smallInteger()->notNull()->defaultValue(1),
            'entity_enabled' => $this->boolean()->notNull()->defaultValue(1),
            'tenant_id' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%node}}');
    }

}
