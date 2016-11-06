<?php

use yii\db\Migration;

/**
 * 站点用户
 *
 * @author hiscaler <hiscaler@gmail.com>
 */
class m160422_163417_create_tenant_user_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%tenant_user}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull()->comment('用户 id'),
            'role' => $this->integer()->notNull()->defaultValue(0)->comment('角色'),
            'rule_id' => $this->integer()->notNull()->defaultValue(0)->comment('规则'),
            'user_group_id' => $this->integer()->notNull()->defaultValue(0)->comment('用户分组'),
            'enabled' => $this->boolean()->notNull()->defaultValue(1)->comment('激活'),
            'tenant_id' => $this->integer()->notNull()->comment('站点 id'),
            'created_at' => $this->integer()->notNull()->comment('添加时间'),
            'created_by' => $this->integer()->notNull()->comment('添加人'),
            'updated_at' => $this->integer()->notNull()->comment('更新时间'),
            'updated_by' => $this->integer()->notNull()->comment('更新人'),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%tenant_user}}');
    }

}
