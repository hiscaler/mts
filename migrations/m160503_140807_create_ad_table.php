<?php

use yii\db\Migration;

class m160503_140807_create_ad_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%ad}}', [
            'id' => $this->primaryKey(),
            'space_id' => $this->integer()->notNull(),
            'name' => $this->string(60)->notNull(),
            'url' => $this->string(200)->notNull(),
            'type' => $this->smallInteger()->notNull(),
            'file_path' => $this->string(100),
            'text' => $this->text(),
            'begin_datetime' => $this->integer()->notNull(),
            'end_datetime' => $this->integer()->notNull(),
            'message' => $this->string(255),
            'views_count' => $this->integer()->notNull()->defaultValue(0),
            'clicks_count' => $this->integer()->notNull()->defaultValue(0),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'enabled' => $this->boolean()->notNull()->defaultValue(1),
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
        $this->dropTable('{{%ad}}');
    }

}
