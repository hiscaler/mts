<?php

use yii\db\Migration;

/**
 * 站点模块
 */
class m160423_150201_create_tenant_module_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%tenant_module}}', [
            'tenant_id' => $this->integer()->notNull(),
            'module_name' => $this->string(30)->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%tenant_module}}');
    }

}
