<?php

use yii\db\Migration;

/**
 * Handles the creation for table `tenant_access_token_table`.
 */
class m161106_093613_create_tenant_access_token_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%tenant_access_token}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull()->comment('名称'),
            'access_token' => $this->string(32)->notNull()->comment('Access Token'),
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
        $this->dropTable('{{%tenant_access_token}}');
    }

}
