<?php

namespace app\models;

use PDO;
use Yii;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\web\HttpException;

class BaseTree extends \yii\db\ActiveRecord
{

    use UserTrait;

    const SEPARATOR = '~!@';

    private $_oldParentId;
    private $_oldLevel;
    protected $valueFieldName = 'name';

    public function checkParentId($attribute, $params)
    {
        if (!$this->isNewRecord && $this->parent_id == $this->id) {
            $this->addError($attribute, '父级不能为自己。');
        }
    }

    /**
     * 获取上级节点信息
     * @param integer $id
     * @return array
     */
    public static function getParents($id)
    {
        $closureTableName = '{{%' . Inflector::camel2id(StringHelper::basename(get_called_class()), '_') . '_closure}}';
        return Yii::$app->getDb()->createCommand('SELECT t.* FROM ' . static::tableName() . ' t JOIN ' . $closureTableName . ' c ON [[t.id]] = [[c.parent_id]] WHERE [[c.child_id]] = :childId AND [[c.parent_id]] <> :parentId')->bindValues([
                ':childId' => (int) $id,
                ':parentId' => (int) $id
            ])->queryAll();
    }

    protected static function sortItems($tree)
    {
        $ret = [];
        if (isset($tree['children']) && is_array($tree['children'])) {
            $children = $tree['children'];
            unset($tree['children']);
            $ret[] = $tree;
            foreach ($children as $child) {
                $ret = array_merge($ret, self::sortItems($child, 'children'));
            }
        } else {
            unset($tree['children']);
            $ret[] = $tree;
        }

        return $ret;
    }

    /**
     * 获取上级节点编号
     * @param integer $id
     * @return array
     */
    public static function getParentIds($id)
    {
        $closureTableName = '{{%' . Inflector::camel2id(StringHelper::basename(get_called_class()), '_') . '_closure}}';
        return Yii::$app->getDb()->createCommand('SELECT [[parent_id]] FROM ' . $closureTableName . ' WHERE [[child_id]] = :child_id AND [[parent_id]] <> :parentId')->bindValues([
                ':childId' => (int) $id,
                ':parentId' => (int) $id
            ])->queryColumn();
    }

    /**
     * 获取子节点信息
     * @param integer $id
     * @return array
     */
    public static function getChildren($id)
    {
        $closureTableName = '{{%' . Inflector::camel2id(StringHelper::basename(get_called_class()), '_') . '_closure}}';
        return Yii::$app->getDb()->createCommand('SELECT t.* FROM ' . static::tableName() . ' t JOIN ' . $closureTableName . ' c ON [[t.id]] = [[c.child_id]] WHERE [[c.parent_id]] = :parentId AND [[c.child_id]] <> :childId')->bindValues([
                ':parentId' => (int) $id,
                ':childId' => (int) $id
            ])->queryAll();
    }

    /**
     * 获取子节点编号
     * @param integer $id
     * @return array
     */
    public static function getChildrenIds($id)
    {
        $closureTableName = '{{%' . Inflector::camel2id(StringHelper::basename(get_called_class()), '_') . '_closure}}';
        return Yii::$app->getDb()->createCommand("SELECT [[child_id]] FROM {$closureTableName} WHERE [[parent_id]] = :parentId AND [[child_id]] <> :childId")->bindValues([
                ':parentId' => (int) $id,
                ':childId' => (int) $id
            ])->queryColumn();
    }

    // Events
    public function afterFind()
    {
        parent::afterFind();
        $this->_oldParentId = $this->parent_id;
        $this->_oldLevel = $this->level;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->parent_id == 0) {
                $this->level = 0;
                $this->parent_ids = $this->parent_names = null;
            } else {
                $level = \Yii::$app->getDb()->createCommand('SELECT [[level]] FROM ' . static::tableName() . ' WHERE [[id]] = :id')->bindValue(':id', $this->parent_id, PDO::PARAM_INT)->queryScalar();
                $this->level = $level + 1;
            }

            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $treeTableName = static::tableName();
        $closureTableName = '{{%' . Inflector::camel2id(StringHelper::basename(get_called_class()), '_') . '_closure}}';
        $db = Yii::$app->getDb();
        $transaction = $db->beginTransaction();
        try {
            if (!$insert) {
                if ($this->_oldParentId != $this->parent_id) {
                    $db->createCommand('DELETE a FROM ' . $closureTableName . ' AS a
JOIN ' . $closureTableName . ' AS d ON [[a.child_id]] = [[d.child_id]]
LEFT JOIN ' . $closureTableName . ' AS x
ON [[x.parent_id]] = [[d.parent_id]] AND [[x.child_id]] = [[a.parent_id]]
WHERE [[d.parent_id]] = :parentId AND [[x.parent_id]] IS NULL')->bindValue(':parentId', $this->id, PDO::PARAM_INT)->execute();
                    $db->createCommand('INSERT INTO ' . $closureTableName . ' ([[parent_id]], [[child_id]], [[level]])
SELECT [[supertree.parent_id]], [[subtree.child_id]], [[supertree.level]] + [[subtree.level]] + 1
FROM ' . $closureTableName . ' AS supertree JOIN ' . $closureTableName . ' AS subtree
WHERE [[subtree.parent_id]] = :id AND [[supertree.child_id]] = :parentId')->bindValues([
                        ':id' => $this->id,
                        ':parentId' => $this->parent_id
                    ])->execute();
                }

                // 更新层级变化
                if ($this->level != $this->_oldLevel) {
                    $level = (int) $this->level - (int) $this->_oldLevel;
                    $effectedRows = self::getChildrenIds($this->id);
                    if (count($effectedRows) > 1) {
                        if ($level) {
                            $db->createCommand('UPDATE ' . $treeTableName . ' SET [[level]] = [[level]] + ' . (int) $level . ' WHERE [[id]] IN (' . implode(',', $effectedRows) . ') AND [[id]] <> :id')->bindValue(':id', $this->id, PDO::PARAM_INT)->execute();
                        } else {
                            $db->createCommand('UPDATE ' . $treeTableName . ' SET [[level]] = [[level]] - ' . (int) $level . ' WHERE [[id]] IN (' . implode(',', $effectedRows) . ' AND [[id]] <> :id')->bindValue(':id', $this->id, PDO::PARAM_INT)->execute();
                        }
                    }
                }
            } else {
                $db->createCommand('INSERT INTO ' . $closureTableName . ' ([[parent_id]], [[child_id]], [[level]])
SELECT [[t.parent_id]], ' . $this->id . ', [[t.level]] + 1
FROM ' . $closureTableName . ' AS t
WHERE [[t.child_id]] = ' . $this->parent_id . ' UNION ALL SELECT ' . $this->id . ', ' . $this->id . ', 0')->bindValues([
                    ':pid1' => $this->id,
                    ':pid2' => $this->id
                ])->execute();
            }

            // 给 parent_ids, parent_names 赋值
            if ($this->parent_id != 0) {
                $parents = static::getParents($this->id);
                if ($parents) {
                    $parentIds = $parentNames = [];
                    foreach ($parents as $parent) {
                        $parentIds[] = $parent['id'];
                        $parentNames[] = $parent[$this->valueFieldName];
                    }
                    $db->createCommand()->update($treeTableName, [
                        'parent_ids' => implode(static::SEPARATOR, $parentIds),
                        'parent_names' => implode(static::SEPARATOR, $parentNames)
                        ], 'id = :id', [':id' => $this->id])->execute();
                }
            }
            $transaction->commit();
        } catch (HttpException $e) {
            $transaction->rollBack();
            throw new HttpException($e->getMessage());
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();
        $tableName = '{{%' . Inflector::camel2id(StringHelper::basename(get_called_class()), '_') . '_closure}}';
        Yii::$app->getDb()->createCommand('DELETE link FROM ' . $tableName . ' p, ' . $tableName . ' link, ' . $tableName . ' c WHERE [[p.parent_id]] = [[link.parent_id]] AND [[c.child_id]] = [[link.child_id]] AND [[p.child_id]] = :parentId AND [[c.parent_id]] = :childId')->bindValues([
            ':parentId' => $this->id,
            ':childId' => $this->id
        ])->execute();
    }

}
