<?php

use yii\db\Migration;

/**
 * 分组设定
 */
class m160424_091420_create_group_option_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%group_option}}', [
            'id' => $this->primaryKey(),
            'group_name' => $this->string(30)->notNull(),
            'text' => $this->string(30)->notNull(),
            'value' => $this->string(30)->notNull(),
            'alias' => $this->string(30),
            'enabled' => $this->boolean()->notNull()->defaultValue(0),
            'defaulted' => $this->boolean()->notNull()->defaultValue(0),
            'ordering' => $this->smallInteger()->notNull()->defaultValue(0),
            'description' => $this->string(100),
            'tenant_id' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'deleted_by' => $this->integer(),
            'deleted_at' => $this->integer(),
        ]);
    }

    public function down()
    {
        $this->dropTable('group_option_table');
    }

}
