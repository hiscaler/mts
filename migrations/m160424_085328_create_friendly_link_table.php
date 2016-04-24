<?php

use yii\db\Migration;

class m160424_085328_create_friendly_link_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%friendly_link}}', [
            'id' => $this->primaryKey(),
            'group_id' => $this->smallInteger()->notNull()->defaultValue(0),
            'type' => $this->smallInteger()->notNull()->defaultValue(0),
            'title' => $this->string(30)->notNull(),
            'description' => $this->string(),
            'url' => $this->string(160)->notNull(),
            'url_open_target' => $this->string(6)->notNull(),
            'logo_path' => $this->string(100),
            'ordering' => $this->integer()->notNull()->defaultValue(0),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
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

    public function down()
    {
        $this->dropTable('{{%friendly_link}}');
    }

}
