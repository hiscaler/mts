<?php

use yii\db\Migration;

/**
 * Handles the creation for table `auth_category`.
 */
class m161024_134739_create_auth_category_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%auth_category}}', [
            'user_id' => $this->integer()->notNull()->comment('用户 id'),
            'category_id' => $this->integer()->notNull()->comment('分类 id'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%auth_category}}');
    }

}
