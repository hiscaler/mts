<?php

use yii\db\Migration;

/**
 * 用户登录日志
 */
class m160423_162305_create_user_login_log_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%user_login_log}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'login_ip' => $this->integer()->notNull(),
            'status' => $this->boolean()->notNull(),
            'client_informations' => $this->string() . ' NOT NULL',
            'login_at' => $this->integer()->notNull(),
        ]);
    }

}
