<?php

use yii\db\Migration;

/**
 * 文档标签关联列表
 */
class m160424_145954_create_archive_label_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%archive_label}}', [
            'id' => $this->primaryKey(),
            'archive_id' => $this->integer()->notNull(),
            'model_name' => $this->string(60)->notNull(),
            'label_id' => $this->integer()->notNull(),
            'tenant_id' => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%archive_label}}');
    }

}
