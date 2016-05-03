<?php

use yii\db\Migration;

/**
 * Grid View 列配置
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class m160503_142504_create_grid_column_config_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%grid_column_config}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull(),
            'attribute' => $this->string(30)->notNull(),
            'css_class' => $this->string(),
            'css_style' => $this->string(),
            'visible' => $this->boolean()->notNull()->defaultValue(1),
            'user_id' => $this->integer()->notNull(),
            'tenant_id' => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%grid_column_config}}');
    }

}
