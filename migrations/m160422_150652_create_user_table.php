<?php

use yii\db\Migration;

/**
 * 用户表
 */
class m160422_150652_create_user_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'type' => $this->smallInteger(5)->notNull()->defaultValue(1),
            'username' => $this->string(20)->notNull(),
            'nickname' => $this->string(20)->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string(60)->notNull(),
            'password_reset_token' => $this->string(60),
            'email' => $this->string(40),
            'status' => $this->smallInteger(5)->notNull()->defaultValue(0),
            'register_ip' => $this->integer(11)->notNull(),
            'login_count' => $this->integer(11)->notNull()->defaultValue(0),
            'last_login_ip' => $this->integer(11),
            'last_login_datetime' => $this->integer(11),
            'last_change_password_time' => $this->integer(11),
            'created_by' => $this->integer(11)->notNull()->defaultValue(0),
            'created_at' => $this->integer(11)->notNull(),
            'updated_by' => $this->integer(11)->notNull()->defaultValue(0),
            'updated_at' => $this->integer(11)->notNull(),
            'deleted_by' => $this->integer(11),
            'deleted_at' => $this->integer(11),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }

}
