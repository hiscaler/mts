<?php

use yii\db\Migration;

class m160505_135057_create_slide_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%slide}}', [
            'id' => $this->primaryKey(),
            'group_id' => $this->smallInteger()->notNull()->defaultValue(0),
            'title' => $this->string()->notNull(),
            'url' => $this->string(200)->notNull(),
            'url_open_target' => $this->string(6)->notNull()->defaultValue('_blank'),
            'picture' => $this->string(100)->notNull(),
            'enabled' => $this->boolean()->notNull()->defaultValue(1),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'ordering' => $this->integer()->notNull()->defaultValue(0),
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
        $this->dropTable('{{%slide}}');
    }

}
