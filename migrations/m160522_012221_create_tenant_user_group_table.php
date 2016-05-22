<?php

use yii\db\Migration;

/**
 * 租赁站点用户分组
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class m160522_012221_create_tenant_user_group_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%tenant_user_group}}', [
            'id' => $this->primaryKey(),
            'alias' => $this->string(30)->notNull(),
            'name' => $this->string(30)->notNull(),
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

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%tenant_user_group}}');
    }

}
