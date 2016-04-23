<?php

use yii\db\Migration;

/**
 * 推送位管理
 */
class m160423_145833_create_label_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%label}}', [
            'id' => $this->primaryKey(),
            'alias' => $this->string(30)->notNull()->unique(),
            'name' => $this->string(30)->notNull(),
            'frequency' => $this->integer()->notNull()->defaultValue(0),
            'ordering' => $this->integer()->notNull()->defaultValue(0),
            'enabled' => $this->boolean()->notNull()->defaultValue(1),
            'tenant_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%label}}');
    }

}
