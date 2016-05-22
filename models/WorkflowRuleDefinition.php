<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%workflow_rule_definition}}".
 *
 * @property integer $rule_id
 * @property integer $ordering
 * @property integer $user_id
 * @property integer $user_group_id
 * @property integer $enabled
 */
class WorkflowRuleDefinition extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%workflow_rule_definition}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rule_id', 'ordering', 'user_id', 'user_group_id'], 'required'],
            ['ordering', 'unique', 'targetAttribute' => ['ordering', 'rule_id']],
            [['rule_id', 'ordering', 'user_id', 'user_group_id', 'enabled'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rule_id' => Yii::t('workflow', 'Rule'),
            'ordering' => Yii::t('workflow', 'Ordering'),
            'user_id' => Yii::t('workflow', 'User'),
            'user_group_id' => Yii::t('workflow', 'User Group'),
            'enabled' => Yii::t('app', 'Enabled'),
        ];
    }

    /**
     * 流程规则
     * @return ActiveRecord
     */
    public function getRule()
    {
        return $this->hasOne(WorkflowRule::className(), ['id' => 'rule_id']);
    }

    /**
     * 审核人
     * @return ActiveRecord
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * 审核分组
     * @return ActiveRecord
     */
    public function getUserGroup()
    {
        return $this->hasOne(TenantUserGroup::className(), ['id' => 'user_group_id']);
    }

}
