<?php

use yii\db\Migration;

/**
 * Handles the creation of table `classic_case`.
 */
class m180107_035538_create_classic_case_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%classic_case}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull()->comment('案例名称'),
            'keywords' => $this->string()->comment('关键词'),
            'description' => $this->text()->comment('简介'),
            'picture_path' => $this->string(100)->comment('案例图片'),
            'content' => $this->text()->notNull()->comment('案例说明'),
            'clicks_count' => $this->integer()->notNull()->defaultValue(0)->comment('点击次数'),
            'enabled' => $this->boolean()->notNull()->defaultValue(1)->comment('激活'),
            'ordering' => $this->integer()->notNull()->defaultValue(0)->comment('排序'),
            'published_at' => $this->integer()->notNull()->comment('发布时间'),
            'tenant_id' => $this->smallInteger()->notNull()->comment('所属站点'),
            'created_at' => $this->integer()->notNull()->comment('添加时间'),
            'created_by' => $this->integer()->notNull()->comment('添加人'),
            'updated_at' => $this->integer()->notNull()->comment('更新时间'),
            'updated_by' => $this->integer()->notNull()->comment('更新人'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%classic_case}}');
    }
}
