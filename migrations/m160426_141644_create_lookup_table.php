<?php

use yii\db\Migration;

/**
 * 基本设置
 */
class m160426_141644_create_lookup_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%lookup}}', [
            'id' => $this->primaryKey(),
            'label' => $this->string(30)->notNull(),
            'description' => $this->string(),
            'value' => $this->text()->notNull(),
            'return_type' => $this->smallInteger()->notNull()->defaultValue(1),
            'enabled' => $this->boolean()->notNull()->defaultValue(1),
            'tenant_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%lookup}}');
    }

}
