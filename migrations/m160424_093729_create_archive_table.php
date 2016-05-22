<?php

use yii\db\Migration;

class m160424_093729_create_archive_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%archive}}', [
            'id' => $this->primaryKey(),
            'node_id' => $this->integer()->notNull(),
            'model_name' => $this->string(30)->notNull(),
            'title' => $this->string(255)->notNull(),
            'keywords' => $this->string(255),
            'description' => $this->text(),
            'tags' => $this->string(200),
            'has_thumbnail' => $this->boolean()->notNull()->defaultValue(0),
            'thumbnail' => $this->string(100),
            'author' => $this->string(20)->notNull(),
            'source' => $this->string(30)->notNull(),
            'status' => $this->smallInteger()->defaultValue(0)->notNull(),
            'enabled' => $this->boolean()->defaultValue(1)->notNull(),
            'published_datetime' => $this->integer()->notNull(),
            'clicks_count' => $this->integer()->notNull()->defaultValue(0),
            'enabled_comment' => $this->boolean()->notNull()->defaultValue(0),
            'comments_count' => $this->integer()->notNull()->defaultValue(0),
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
        $this->dropTable('{{%archive}}');
    }

}
