<?php

namespace app\models;

use PDO;
use yadjet\helpers\ArrayHelper;
use yadjet\helpers\TreeFormatHelper;
use Yii;
use yii\helpers\Inflector;

/**
 * This is the model class for table "{{%node}}".
 *
 * @property integer $id
 * @property string $alias
 * @property string $name
 * @property string $model_name
 * @property string $parameters
 * @property integer $parent_id
 * @property string $parent_ids
 * @property string $parent_names
 * @property integer $level
 * @property integer $ordering
 * @property integer $direct_data_count
 * @property integer $relation_data_count
 * @property integer $enabled
 * @property integer $entity_status
 * @property integer $entity_enabled
 * @property integer $tenant_id
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_by
 * @property integer $updated_at
 * @property integer $deleted_at
 * @property integer $deleted_by
 */
class Node extends BaseTree
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%node}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias', 'name', 'model_name', 'parent_id', 'ordering', 'entity_status'], 'required'],
            [['alias', 'name', 'parameters'], 'trim'],
            ['parameters', 'default', 'value' => function() {
                    $modelName = explode('-', $this->model_name);
                    $controller = strtolower(Inflector::pluralize(end($modelName)));
                    return "i:{$controller}/index~~/{$controller}/index.twig
l:{$controller}/list~~/{$controller}/list.twig
v:{$controller}/view~<id:\d+>~/{$controller}/view.twig~.html";
                }],
            ['alias', 'match', 'pattern' => '/^[a-zA-Z0-9]{1,}[_-]{0,1}[a-zA-Z0-9-\/]{0,}[a-zA-Z0-9]{0,}+$/'],
            [['enabled', 'entity_enabled'], 'boolean'],
            [['parent_id', 'level', 'ordering', 'direct_data_count', 'relation_data_count', 'entity_status', 'tenant_id', 'created_by', 'created_at', 'updated_by', 'updated_at', 'deleted_at', 'deleted_by'], 'integer'],
            [['alias', 'name', 'parameters', 'parent_ids', 'parent_names'], 'string', 'max' => 255],
            [['model_name'], 'string', 'max' => 60],
            ['parent_id', 'checkParentId'],
            ['alias', 'checkAlias'],
            ['alias', 'unique', 'targetAttribute' => ['alias', 'tenant_id']],
        ];
    }

    /**
     * 别名验证
     * @param string $attribute
     * @param array $params
     */
    public function checkAlias($attribute, $params)
    {
        if ($this->parent_id == 0 && strpos($this->alias, '/') !== false) {
            $this->addError($attribute, '顶级结点别名中不能包含“/”符号。');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'name' => Yii::t('node', 'Name'),
            'parameters' => Yii::t('node', 'parameters'),
            'parent_id' => Yii::t('node', 'Parent'),
            'level' => Yii::t('node', 'Level'),
            'parameters' => Yii::t('node', 'Paramaters'),
            'direct_data_count' => Yii::t('node', 'Direct Data Count'),
            'relation_data_count' => Yii::t('node', 'Relation Data Count'),
            'entity_status' => Yii::t('node', 'Entity Status'),
            'entity_enabled' => Yii::t('node', 'Entity Enabled'),
        ]);
    }

    /**
     * 获取节点树，如果有提供 modelName 的话，则只显示和 modelName 相关的节点数据
     * @param string $modelName
     * @return array
     */
    public static function hashMapItems($modelName = null)
    {
        $tenantId = MTS::getTenantId();
        $items = [];
        $sql = 'SELECT [[id]], [[name]], [[alias]], [[parent_id]], [[level]], [[ordering]] FROM {{%node}} WHERE ';
        $bindValues = [
            ':tenantId' => $tenantId
        ];
        if ($modelName === null) {
            $sql .= '[[tenant_id]] = :tenantId';
        } else {
            $sql .= '[[id]] IN (SELECT [[parent_id]] FROM {{%node_closure}} WHERE [[child_id]] IN (SELECT [[id]] FROM {{%node}} WHERE [[model_name]] = :modelName AND [[tenant_id]] = :tenantId))';
            $bindValues[':modelName'] = trim($modelName);
        }
        $sql .= ' AND [[id]] IN (SELECT [[node_id]] FROM {{%user_auth_node}} WHERE [[user_id]] = :userId)';
        $bindValues[':userId'] = Yii::$app->getUser()->getId();
        $rawData = Yii::$app->getDb()->createCommand($sql)->bindValues($bindValues)->queryAll();
        if ($rawData) {
            $rawData = TreeFormatHelper::dumpArrayTree(ArrayHelper::toTree($rawData, 'id'));
            foreach ($rawData as $data) {
                $items[$data['id']] = $data['levelstr'] . '┄' . $data['name'] . " [ {$data['alias']} ]" . '（' . $data['ordering'] . '）';
            }
        }

        return $items;
    }

    /**
     * 获取根节点信息（树形结构）
     * @return array
     */
    public static function parentOptions($root = true)
    {
        $items = [];
        if ($root) {
            $items[] = Yii::t('node', 'Root');
        }
        $rawData = Yii::$app->getDb()->createCommand('SELECT [[id]], [[name]], [[alias]], [[parent_id]], [[level]], [[ordering]] FROM ' . static::tableName() . ' WHERE [[tenant_id]] = :tenantId')->bindValue(':tenantId', MTS::getTenantId(), PDO::PARAM_INT)->queryAll();
        $tree = [];
        foreach ($rawData as $data) {
            $tree[] = [
                'id' => $data['id'],
                'alias' => $data['alias'],
                'name' => $data['name'],
                'level' => $data['level'],
                'parent_id' => $data['parent_id'],
                'ordering' => $data['ordering'],
            ];
        }
        if ($tree) {
            $fixedData = ArrayHelper::toTree($tree, 'id');
            $fixedData = TreeFormatHelper::dumpArrayTree($fixedData);
            foreach ($fixedData as $data) {
                $items[$data['id']] = $data['levelstr'] . '┄' . strip_tags($data['name']) . " [ {$data['alias']} ]" . '（' . (string) $data['ordering'] . '）';
            }
        }

        return $items;
    }

    /**
     * 获取根据排序和树形节点处理后的节点数据，用于在 table 中展示整棵节点树
     * @return array
     */
    public static function getOrderedItems()
    {
        $nodes = Yii::$app->getDb()->createCommand('SELECT t.*, cu.nickname AS creater_nickname, uu.nickname AS updater_nickname, du.nickname AS deleter_nickname FROM {{%node}} t
            LEFT JOIN {{%user}} cu ON [[t.created_by]] = [[cu.id]]
            LEFT JOIN {{%user}} uu ON [[t.updated_by]] = [[uu.id]]
            LEFT JOIN {{%user}} du ON [[t.deleted_by]] = [[du.id]]
            WHERE [[t.tenant_id]] = :tenantId ORDER BY [[t.parent_id]], [[t.ordering]]')->bindValue(':tenantId', MTS::getTenantId(), PDO::PARAM_INT)->queryAll();
        if ($nodes) {
            $nodes = static::sortItems(['children' => ArrayHelper::toTree($nodes, 'id')]);
            unset($nodes[0]);

            return $nodes;
        } else {
            return [];
        }
    }

    /**
     * 获取顶级节点信息（格式：['id' => 1, 'name' => 'root name']），如果不存在则返回 null
     * @param integer $nodeId
     * @return mixed
     */
    public static function getRoot($nodeId)
    {
        $node = Yii::$app->getDb()->createCommand('SELECT [[id]], [[name]], [[parent_id]] FROM {{%node}} WHERE [[id]] = :id')->bindValue(':id', (int) $nodeId, PDO::PARAM_INT)->queryOne();
        if ($node === false) {
            return null;
        } elseif ($node['parent_id'] == 0) {
            return [
                'id' => $node['id'],
                'name' => $node['name']
            ];
        } else {
            $parentNode = Yii::$app->getDb()->createCommand('SELECT [[id]], [[name]] FROM {{%node}} WHERE [[tenant_id]] = :tenantId AND [[parent_id]] = 0 AND [[id]] IN (SELECT [[parent_id]] FROM {{%node_closure}} WHERE [[child_id]] = :id)')->bindValues([
                    ':tenantId' => MTS::getTenantId(),
                    ':id' => (int) $nodeId
                ])->queryOne();
            if ($parentNode === false) {
                return null;
            } else {
                return [
                    'id' => $parentNode['id'],
                    'name' => $parentNode['name']
                ];
            }
        }
    }

    /**
     * 获取顶级结点 id
     * @param integer $nodeId
     * @return mixed
     */
    public static function getRootId($nodeId)
    {
        $node = self::getRoot($nodeId);
        return $node !== null ? $node['id'] : null;
    }

    /**
     * 获取顶级结点名称
     * @param integer $nodeId
     * @return mixed
     */
    public static function getRootName($nodeId)
    {
        $node = self::getRoot($nodeId);
        return $node !== null ? $node['name'] : null;
    }

    public static function getBreadcrumbs($id)
    {
        $breadcrumbs = Yii::$app->getDb()->createCommand('SELECT A.* FROM {{%nodes}} t INNER JOIN {{%node_closure}} c ON [[t.id]] = [[c.parent_id]] WHERE [[c.child_id]] = :childId ORDER BY [[c.level]] DESC')->bindValu(':childId', (int) $id)->queryAll();

        return $breadcrumbs;
    }

    /**
     * 获取实体记录默认值
     * @param integer $nodeId
     */
    public static function getEntityDefaultValues($nodeId)
    {
        $defaultValues = [
            'status' => Constant::STATUS_PENDING,
            'enabled' => Constant::BOOLEAN_FALSE
        ];
        $data = Yii::$app->getDb()->createCommand('SELECT [[entity_status]] AS [[status]], [[entity_enabled]] AS [[enabled]] FROM {{%node}} WHERE [[id]] = :id')->bindValue(':id', (int) $nodeId, PDO::PARAM_INT)->queryOne();
        if ($data !== false) {
            $defaultValues = $data;
        }

        return $defaultValues;
    }

    /**
     * 实体默认状态
     * @return array
     */
    public static function entityStatusOptions()
    {
        return [
            Constant::STATUS_PENDING => Yii::t('app', 'Pending'),
            Constant::STATUS_PUBLISHED => Yii::t('app', 'Published')
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->direct_data_count = 0;
                $this->relation_data_count = 0;

                if ($this->parent_id != 0) {
                    $modelName = explode('-', $this->model_name);
                    $controllerId = strtolower(Inflector::pluralize(end($modelName)));
                    $this->parameters = "i:{$controllerId}/index~~/{$controllerId}/list.twig\r
l:{$controllerId}/list~~/{$controllerId}/list.twig\r
v:{$controllerId}/view~<id:\d+>~/{$controllerId}/view.twig~.html";
                }
            }

            if ($this->parent_id != 0 && strpos($this->alias, '/') === false) {
                // 别名处理
                $parentAlias = Yii::$app->getDb()->createCommand('SELECT [[alias]] FROM {{%node}} WHERE [[id]] = :id')->bindValue(':id', $this->parent_id, PDO::PARAM_INT)->queryScalar();
                $this->alias = "{$parentAlias}/{$this->alias}";
            }

            return true;
        } else {
            return false;
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();
        Yii::$app->getDb()->createCommand()->delete('{{%auth_node}}', ['node_id' => $this->id])->execute();
    }

}
