<?php

namespace app\models;

use yadjet\helpers\StringHelper;
use yadjet\helpers\UtilHelper;
use Yii;
use yii\db\ActiveQueryInterface;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;

/**
 * @property string $keywords
 * @property string $entityAttributes
 * @property string $entityNodeIds
 * @property string $entityNodeNames
 * @property integer $isDraft
 * @property integer $tenant_id
 */
class BaseActiveRecord extends ActiveRecord
{

    /**
     * 默认排序值
     */
    const DEFAULT_ORDERING_VALUE = 10000;

    private $_oldEntityAttributes = [];
    public $entityAttributes = [];
    private $_oldEntityNodeIds;
    private $_oldEntityNodeNames;
    public $entityNodeIds;
    public $entityNodeNames;
    private $_oldNodeId;
    public $images = [];
    public $isDraft = Constant::BOOLEAN_FALSE; // 记录是否为草稿
    public $content_image_number = 1; // 从文本内容中获取第几章图片作为缩略图

    /**
     * `app\model\Post` To `app-model-Post`
     * @param string $className
     * @return string
     */

    public static function className2Id($className = null)
    {
        if ($className === null) {
            $className = static::className();
        }
        return str_replace('\\', '-', $className);
    }

    /**
     * `app-model-Post` To `app\model\Post`
     * @param string $id
     * @return string
     */
    public static function id2ClassName($id)
    {
        return str_replace('-', '\\', $id);
    }

    public function rules()
    {
        $rules = [
            [['entityAttributes', 'entityNodeIds'], 'safe'],
            ['isDraft', 'boolean'],
            ['content_image_number', 'safe']
        ];
        if ($this->hasAttribute('node_id')) {
            $rules = array_merge($rules, [
                [['node_id'], 'integer'],
                [['node_id'], 'default', 'value' => 0],
            ]);
        }
        if ($this->hasAttribute('tags')) {
            $rules[] = [['tags'], 'trim'];
            $rules[] = [['tags'], 'string', 'max' => 255];
            $rules[] = [['tags'], 'normalizeTags'];
        }
        if ($this->hasAttribute('keywords')) {
            $rules[] = [['keywords'], 'trim'];
            $rules[] = [['keywords'], 'string', 'max' => 255];
            $rules[] = [['keywords'], 'normalizeKeywords'];
        }
        if ($this->hasAttribute('tenant_id')) {
            $rules[] = [['tenant_id'], 'integer'];
        }
        if (Workflow::isUsable() && $this->hasAttribute('task_id')) {
            $rules[] = [['task_id'], 'integer'];
            $rules[] = [['task_id'], 'default', 'value' => 0]; // 如果工作流没有相关规则定义，默认任务编号为零值
        }

        return $rules;
    }

    private function normalizeWords($value)
    {
        if (!empty($value)) {
            $value = UtilHelper::array2string(array_unique(UtilHelper::string2array(StringHelper::makeSemiangle($value))));
        }

        return $value;
    }

    /**
     * Normalizes the user-entered tags.
     */
    public function normalizeTags($attribute, $params)
    {
        if (!empty($this->tags)) {
            $this->tags = $this->normalizeWords($this->tags);
        }
    }

    /**
     * Normalizes the user-entered keywords.
     */
    public function normalizeKeywords($attribute, $params)
    {
        if (!empty($this->keywords)) {
            $this->keywords = $this->normalizeWords($this->keywords);
        }
    }

    public function getNode()
    {
        return $this->hasOne(Node::className(), ['id' => 'node_id'])->select(['id', 'name']);
    }

    /**
     * 自定义属性
     * @return ActiveRecord
     */
    public function getCustomeAttributes()
    {
        return $this->hasMany(Attribute::className(), ['id' => 'attribute_id'])
                ->select(['id', 'name'])
                ->viaTable('{{%entity_attribute}}', ['entity_id' => 'id'], function ($query) {
                    $query->where(['entity_name' => static::className2Id()]);
                }
        );
    }

    /**
     * 流程任务
     * @return ActiveRecord
     */
    public function getTask()
    {
        return $this->hasOne(WorkflowTask::className(), ['id' => 'task_id']);
    }

    /**
     * Creater relational
     * @return ActiveQueryInterface the relational query object.
     */
    public function getCreater()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by'])->select(['id', 'nickname']);
    }

    /**
     * Updater relational
     * @return ActiveQueryInterface the relational query object.
     */
    public function getUpdater()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by'])->select(['id', 'nickname']);
    }

    /**
     * Deleter relational
     * @return ActiveQueryInterface the relational query object.
     */
    public function getDeleter()
    {
        return $this->hasOne(User::className(), ['id' => 'deleted_by'])->select(['id', 'nickname']);
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'short_title' => Yii::t('app', 'Short Title'),
            'id' => Yii::t('app', 'ID'),
            'tags' => Yii::t('app', 'Tag'),
            'alias' => Yii::t('app', 'Alias'),
            'ordering' => Yii::t('app', 'Ordering'),
            'node_id' => Yii::t('app', 'Node'),
            'group_id' => Yii::t('app', 'Group'),
            'keywords' => Yii::t('app', 'Page Keywords'),
            'description' => Yii::t('app', 'Page Description'),
            'content' => Yii::t('app', 'Content'),
            'screenshot_path' => Yii::t('app', 'Screenshot'),
            'picture_path' => Yii::t('app', 'Picture'),
            'hits_count' => Yii::t('app', 'Hits Count'),
            'status' => Yii::t('app', 'Status'),
            'enabled' => Yii::t('app', 'Enabled'),
            'task.status' => Yii::t('app', 'Task Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'entityAttributes' => Yii::t('app', 'Entity Attributes'),
            'entityNodeIds' => Yii::t('app', 'Relation Node'),
            'model_name' => Yii::t('app', 'Model Name'),
            'isDraft' => Yii::t('app', 'Draft'),
            'tenant_id' => Yii::t('app', 'Tenant ID'),
            'content_image_number' => Yii::t('app', 'Content Image Number'),
        ];
    }

    // Events
    public function afterFind()
    {
        parent::afterFind();
        if (!$this->isNewRecord) {
            $this->entityAttributes = Label::getEntityAttributeIds($this->id, static::className2Id());
            $this->_oldEntityAttributes = $this->entityAttributes;
            $nodes = \Yii::$app->db->createCommand('SELECT [[n.id]], [[n.name]] FROM {{%entity_node}} t LEFT JOIN {{%node}} n ON [[t.node_id]] = [[n.id]] WHERE [[t.entity_id]] = :entityId AND [[t.entity_name]] = :entityName')->bindValues([':entityId' => (int) $this->id, ':entityName' => static::className2Id()])->queryAll();
            if ($nodes) {
                $ids = [];
                $names = [];
                foreach ($nodes as $node) {
                    $ids[] = $node['id'];
                    $names[] = $node['name'];
                }
                $this->entityNodeIds = $this->_oldEntityNodeIds = implode(',', $ids);
                $this->entityNodeNames = $this->_oldEntityNodeNames = $names;
            }

            if ($this->hasAttribute('status')) {
                $this->isDraft = $this->status == Constant::STATUS_DRAFT;
            }
        }
        if ($this->hasAttribute('node_id')) {
            $this->_oldNodeId = $this->node_id;
        }
    }

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            if ($this->isNewRecord && $this->hasAttribute('tenant_id') && !$this->tenant_id) {
                $this->tenant_id = MTS::getTenantId();
            }

            return true;
        } else {
            return false;
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                // 设置记录的 status 和 enabled 属性值
                if ($this->hasAttribute('status') && $this->hasAttribute('enabled')) {
                    if ($this->hasAttribute('node_id')) {
                        $entityDefaultValues = [
                            'status' => Constant::STATUS_PENDING,
                            'enabled' => Constant::BOOLEAN_FALSE
                        ];
                        if ($this->isDraft) {
                            $entityDefaultValues['status'] = Constant::STATUS_DRAFT;
                        } else {
                            $entityDefaultValues = Node::getEntityDefaultValues($this->node_id);
                        }
                        $this->status = $entityDefaultValues['status'];
                        $this->enabled = $entityDefaultValues['enabled'];
                    } else {
                        // 如果站点用户需要审核流程控制，则状态为待审核，否则为已发布
                        $this->status = MTS::getTenantUserRule() ? Constant::STATUS_PENDING : Constant::STATUS_PUBLISHED;
                        $this->enabled = Option::BOOLEAN_TRUE;
                    }
                }

                $this->created_by = Yii::$app->user->id ? : 0;
                $this->created_at = time();
                if ($this->hasAttribute('updated_at')) {
                    $this->updated_by = $this->created_by;
                    $this->updated_at = $this->created_at;
                }
            } else {
                // 设置记录的 status 和 enabled 属性值
                if ($this->hasAttribute('node_id') && $this->hasAttribute('status') && $this->hasAttribute('enabled') && !$this->isDraft && $this->status == Constant::STATUS_PENDING) {
                    $entityDefaultValues = Node::getEntityDefaultValues($this->node_id);
                    $this->status = $entityDefaultValues['status'];
                    $this->enabled = $entityDefaultValues['enabled'];
                }
                if ($this->hasAttribute('updated_at')) {
                    $this->updated_by = $this->updated_by ? : Yii::$app->user->id;
                    $this->updated_at = time();
                }
            }
            if ($this->hasAttribute('deleted_by') && $this->hasAttribute('deleted_at')) {
                if ($this->hasAttribute('status')) {
                    if ($this->status == Constant::STATUS_DELETED) {
                        $this->deleted_by = Yii::$app->user->id;
                        $this->deleted_at = time();
                    } else {
                        $this->deleted_by = $this->deleted_at = null;
                    }
                } else {
                    $this->deleted_by = $this->deleted_at = null;
                }
            }

            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        // Node Id
        if ($this->hasAttribute('node_id')) {
            if ($insert) {
                if ($this->node_id) {
                    Node::updateAllCounters(['direct_data_count' => 1], 'id = :id', ['id' => (int) $this->node_id]);
                }
            } else {
                if ($this->node_id != $this->_oldNodeId) {
                    if ($this->node_id) {
                        Node::updateAllCounters(['direct_data_count' => 1], 'id = :id', ['id' => (int) $this->node_id]);
                    }
                    if ($this->_oldNodeId) {
                        Node::updateAllCounters(['direct_data_count' => -1], 'id = :id', ['id' => (int) $this->_oldNodeId]);
                    }
                }
            }
        }

        // Entity attributes
        $entityAttributes = $this->entityAttributes;
        if (!is_array($this->_oldEntityAttributes)) {
            $this->_oldEntityAttributes = [];
        }
        if (!is_array($entityAttributes)) {
            $entityAttributes = [];
        }

        if ($insert) {
            $insertAttributes = $entityAttributes;
            $deleteAttributes = [];
        } else {
            $insertAttributes = array_diff($entityAttributes, $this->_oldEntityAttributes);
            $deleteAttributes = array_diff($this->_oldEntityAttributes, $entityAttributes);
        }

        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            // Insert data
            if ($insertAttributes) {
                $rows = [];
                $tenantId = MTS::getTenantId();
                foreach ($insertAttributes as $attributeId) {
                    $rows[] = [$this->id, static::className2Id(), $attributeId, Option::BOOLEAN_TRUE, static::DEFAULT_ORDERING_VALUE, $tenantId];
                }
                if ($rows) {
                    $db->createCommand()->batchInsert('{{%entity_attribute}}', ['entity_id', 'entity_name', 'attribute_id', 'enabled', 'ordering', 'tenant_id'], $rows)->execute();
                    $db->createCommand("UPDATE {{%attribute}} SET [[frequency]] = [[frequency]] + 1 WHERE [[id]] IN (" . implode(', ', ArrayHelper::getColumn($rows, 2)) . ")")->execute();
                }
            }
            // Delete data
            if ($deleteAttributes) {
                $db->createCommand()->delete('{{%entity_attribute}}', [
                    'entity_id' => $this->id,
                    'entity_name' => static::className2Id(),
                    'attribute_id' => $deleteAttributes
                ])->execute();
                $db->createCommand("UPDATE {{%attribute}} SET [[frequency]] = [[frequency]] - 1 WHERE [[id]] IN (" . implode(', ', $deleteAttributes) . ")")->execute();
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();
            throw new HttpException('500', $e->getMessage());
        }

        // Entity nodes
        $entityNodeIds = !empty($this->entityNodeIds) ? explode(',', $this->entityNodeIds) : [];
        $oldEntityNodeIds = !empty($this->_oldEntityNodeIds) ? explode(',', $this->_oldEntityNodeIds) : [];

        if ($insert) {
            $insertNodes = $entityNodeIds;
            $deleteNodes = [];
        } else {
            $insertNodes = array_diff($entityNodeIds, $oldEntityNodeIds);
            $deleteNodes = array_diff($oldEntityNodeIds, $entityNodeIds);
        }

        $transaction->begin();
        try {
            // Insert data
            if ($insertNodes) {
                $rows = [];
                foreach ($insertNodes as $nodeId) {
                    $rows[] = [$this->id, static::className2Id(), $nodeId];
                }
                if ($rows) {
                    $db->createCommand()->batchInsert('{{%entity_node}}', ['entity_id', 'entity_name', 'node_id'], $rows)->execute();
                    $db->createCommand("UPDATE {{%node}} SET [[relation_data_count]] = [[relation_data_count]] + 1 WHERE [[id]] IN (" . implode(', ', ArrayHelper::getColumn($rows, 2)) . ")")->execute();
                }
            }
            // Delete data
            if ($deleteNodes) {
                $db->createCommand()->delete('{{%entity_node}}', [
                    'entity_id' => $this->id,
                    'entity_name' => static::className2Id(),
                    'node_id' => $deleteNodes
                ])->execute();
                $db->createCommand("UPDATE {{%node}} SET [[relation_data_count]] = [[relation_data_count]] - 1 WHERE [[id]] IN (" . implode(', ', $deleteNodes) . ")")->execute();
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();
            new HttpException('500', $e->message);
        }

        // 工作流任务处理
//        if (Workflow::isUsable() && $this->hasAttribute('node_id') && $this->hasAttribute('task_id')) {
//            if (!$this->isDraft) {
//                if ($insert || $this->node_id != $this->_oldNodeId) {
//                    // 如果是新增加或者节点发生变化，均需要重新初始化任务数据
//                    $ruleId = Workflow::getRuleId($this->node_id);
//                    if ($ruleId) {
//                        $taskId = Workflow::insertInitializeTask($this->id, static::className2Id(), $this->status, $ruleId);
//                        Yii::$app->db->createCommand()->update(MTS::modelName2TableName(static::className()), ['task_id' => $taskId], '[[id]] = :id', [':id' => $this->id])->execute();
//                    }
//                    if (!$insert) {
//                        // 处理掉原有的任务
//                        Workflow::manualCloseTask($this->task_id);
//                    }
//                }
//            }
//        }
    }

    public function afterDelete()
    {
        parent::afterDelete();
        // Delete attribute relation data and update attribute frequency value
        $attributes = Yii::$app->db->createCommand('SELECT [[id]], [[attribute_id]] FROM {{%entity_attribute}} WHERE [[entity_id]] = :entityId AND [[entity_name]] = :entityName')->bindValues([
                ':entityId' => $this->id,
                ':entityName' => static::className2Id()
            ])->queryAll();
        if ($attributes) {
            Yii::$app->db->createCommand('DELETE FROM {{%entity_attribute}} WHERE [[id]] IN (' . implode(', ', ArrayHelper::getColumn($attributes, 'id')) . ')')->execute();
            Attribute::updateAll(['frequency' => -1], ['id' => ArrayHelper::getColumn($attributes, 'attribute_id')]);
        }

        // Update node staticstic data
        if ($this->hasAttribute('node_id')) {
            if ($this->node_id) {
                Node::updateAllCounters(['direct_data_count' => -1], 'id = :id', ['id' => (int) $this->node_id]);
            }
            $entityNodeIds = !empty($this->entityNodeIds) ? explode(',', $this->entityNodeIds) : [];
            if ($entityNodeIds) {
                Node::updateAllCounters(['relation_data_count' => -1], ['id' => $entityNodeIds]);
            }
        }
    }

}
