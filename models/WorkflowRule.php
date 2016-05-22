<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%workflow_rule}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $enabled
 * @property integer $tenant_id
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_by
 * @property integer $updated_at
 * @property integer $deleted_at
 * @property integer $deleted_by
 */
class WorkflowRule extends BaseActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%workflow_rule}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['name', 'description'], 'required'],
            [['description'], 'string'],
            [['enabled', 'tenant_id', 'created_by', 'created_at', 'updated_by', 'updated_at', 'deleted_at', 'deleted_by'], 'integer'],
            [['name'], 'string', 'max' => 255]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'name' => Yii::t('workflow', 'Name'),
            'description' => Yii::t('workflow', 'Description'),
        ]);
    }

    // 数据关联定义
    /**
     * 规则定义
     * @return ActiveRecord
     */
    public function getDefinitions()
    {
        return $this->hasMany(WorkflowRuleDefinition::className(), ['rule_id' => 'id']);
    }

    // Events
    public function afterDelete()
    {
        parent::afterDelete();
        // 同步删除规则定义数据
        Yii::$app->getDb()->createCommand()->delete('{{%workflow_rule_definition}}', ['rule_id' => $this->id])->execute();
    }

}
