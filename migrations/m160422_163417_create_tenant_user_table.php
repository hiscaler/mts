<?php

use yii\db\Migration;

/**
 * 站点管理用户
 */
class m160422_163417_create_tenant_user_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%tenant_user}}', [
            'id' => $this->primaryKey(),
            'tenant_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'role' => $this->smallInteger()->notNull()->defaultValue(0),
            'rule_id' => $this->integer()->notNull()->defaultValue(0),
            'enabled' => $this->boolean()->notNull()->defaultValue(1),
            'user_group_id' => $this->integer()->notNull()->defaultValue(0), // 用户所在分组
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%tenant_user}}');
    }

}
