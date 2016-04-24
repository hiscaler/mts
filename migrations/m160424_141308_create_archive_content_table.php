<?php

use yii\db\Migration;

class m160424_141308_create_archive_content_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%archive_content}}', [
            'id' => $this->primaryKey(),
            'archive_id' => $this->integer()->notNull(),
            'content' => $this->text()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%archive_content}}');
    }

}
