<?php

use yii\db\Migration;

/**
 * 文件上传设置
 */
class m160424_090558_create_file_upload_config_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%file_upload_config}}', [
            'id' => $this->primaryKey(),
            'type' => $this->smallInteger()->notNull()->defaultValue(0),
            'model_name' => $this->string(30)->notNull(),
            'attribute' => $this->string(30)->notNull(),
            'extensions' => $this->string(30)->notNull(),
            'min_size' => $this->smallInteger()->notNull()->defaultValue(0),
            'max_size' => $this->smallInteger()->notNull()->defaultValue(0),
            'thumb_width' => $this->smallInteger()->defaultValue(0),
            'thumb_height' => $this->smallInteger()->defaultValue(0),
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
        $this->dropTable('{{%file_upload_config}}');
    }

}
