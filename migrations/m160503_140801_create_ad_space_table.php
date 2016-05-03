<?php

use yii\db\Migration;

class m160503_140801_create_ad_space_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%ad_space}}', [
            'id' => $this->primaryKey(),
            'group_id' => $this->smallInteger()->notNull()->defaultValue(0),
            'alias' => $this->string(60)->notNull(),
            'name' => $this->string(30)->notNull(),
            'width' => $this->smallInteger()->notNull(),
            'height' => $this->smallInteger()->notNull(),
            'description' => $this->string(255)->notNull(),
            'ads_count' => $this->integer()->notNull()->defaultValue(0),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'enabled' => $this->boolean()->notNull()->defaultValue(1),
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
        $this->dropTable('{{%ad_space}}');
    }

}
