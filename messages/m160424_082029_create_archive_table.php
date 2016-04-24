<?php

use yii\db\Migration;

/**
 * 档案管理
 */
class m160424_082029_create_archive_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%archive}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'keyword' => $this->string(255),
            'description' => $this->text(),
            'has_picture' => $this->boolean()->notNull()->defaultValue(0),
            'picture_path' => $this->string(100),
            'author' => $this->string(20)->notNull(),
            'status' => $this->smallInteger()->defaultValue(1)->notNull(),
            'enabled' => $this->boolean()->defaultValue(1)->notNull(),
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
