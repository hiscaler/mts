<?php

use yii\db\Migration;

/**
 * 用户可管理节点
 */
class m160424_104145_create_user_auth_node_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%user_auth_node}}', [
            'user_id' => $this->integer()->notNull(),
            'node_id' => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%user_auth_node}}');
    }

}
