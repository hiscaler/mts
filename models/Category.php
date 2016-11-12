<?php

namespace app\models;

use yadjet\helpers\TreeFormatHelper;
use Yii;
use yii\helpers\Inflector;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property integer $id
 * @property integer $type
 * @property string $alias
 * @property string $name
 * @property integer $parent_id
 * @property integer $level
 * @property string $parent_ids
 * @property string $parent_names
 * @property string $icon_path
 * @property string $description
 * @property integer $enabled
 * @property integer $ordering
 * @property integer $tenant_id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 */
class Category extends BaseActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'ordering'], 'required'],
            [['type', 'parent_id', 'level', 'enabled', 'ordering', 'tenant_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['type', 'parent_id', 'level'], 'default', 'value' => 0],
            [['enabled'], 'boolean'],
            [['enabled'], 'default', 'value' => Constant::BOOLEAN_TRUE],
            [['description'], 'string'],
            [['alias'], 'string', 'max' => 120],
            ['alias', 'match', 'pattern' => '/^[a-z]+[a-z-]+[a-z]$/'],
            [['name'], 'string', 'max' => 30],
            [['parent_ids', 'icon_path'], 'string', 'max' => 100],
            [['parent_names'], 'string', 'max' => 255],
            ['alias', 'unique', 'targetAttribute' => ['alias', 'tenant_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'type' => Yii::t('category', 'Type'),
            'alias' => Yii::t('category', 'Alias'),
            'name' => Yii::t('category', 'Name'),
            'parent_id' => Yii::t('category', 'Parent ID'),
            'level' => Yii::t('category', 'Level'),
            'parent_ids' => Yii::t('category', 'Parent Ids'),
            'parent_names' => Yii::t('category', 'Parent Names'),
            'icon_path' => Yii::t('category', 'Icon'),
            'description' => Yii::t('category', 'Description'),
            'ordering' => Yii::t('app', 'Ordering'),
        ]);
    }

    /**
     * 类别选项
     * @return array
     */
    public static function typeOptions()
    {
        return Lookup::getValue('system.models.category.type', []);
    }

    /**
     * 生成数据缓存
     */
    private function generateCache()
    {
        self::getRawItems(false);
        self::getRawItems(true);
    }

    /**
     * 处理并生成分类数据缓存，供后续代码调取
     * @param boolean $toTree
     * @return array
     */
    private static function getRawItems($toTree = false, $tenantId = null)
    {
        $key = '__category_getRawItems_' . $toTree;
        $cache = Yii::$app->getCache();
        $cacheData = $cache->get($key);
        if ($cacheData !== false) {
            return $cacheData;
        } else {
            $items = [];
            $rawData = Yii::$app->getDb()->createCommand('SELECT [[id]], [[type]], [[alias]], [[name]], [[parent_id]], [[icon_path]], [[enabled]] FROM {{%category}} WHERE [[tenant_id]] = :tenantId ORDER BY [[level]] ASC', [':tenantId' => $tenantId ? : Yad::getTenantId()])->queryAll();
            foreach ($rawData as $data) {
                $items[$data['id']] = [
                    'id' => $data['id'],
                    'type' => $data['type'],
                    'alias' => $data['alias'],
                    'name' => $data['name'],
                    'parent' => $data['parent_id'],
                    'icon' => $data['icon_path'],
                    'enabled' => $data['enabled'] ? true : false,
                    'hasChildren' => false
                ];
                if ($data['parent_id'] && isset($items[$data['parent_id']])) {
                    $items[$data['parent_id']]['hasChildren'] = true;
                }
            }
            if ($toTree) {
                $items = \yadjet\helpers\ArrayHelper::toTree($items, 'id', 'parent', 'children');
            }
            $cache->set($key, $items);

            return $items;
        }
    }

    private static function getRawItemsByType($type = 0, $all = false, $toTree = false)
    {
        $items = [];
        foreach (self::getRawItems($toTree) as $key => $item) {
            if ($item['type'] == $type) {
                if ($all || $item['enabled']) {
                    $items[$key] = $item;
                }
            }
        }
        return $items;
    }

    /**
     * 获取分类项目
     *
     * @param integer $type
     * @param mixed $prompt
     * @param boolean $all
     * @return string
     */
    public static function getTree($type, $prompt = null, $all = false)
    {
        $items = [];
        if ($prompt) {
            $items[] = $prompt;
        }
        $rawData = self::getRawItemsByType($type, $all, true);
        if ($rawData) {
            $rawData = TreeFormatHelper::dumpArrayTree($rawData);
            foreach ($rawData as $data) {
                $items[$data['id']] = $data['levelstr'] . $data['name'];
            }
        }

        return $items;
    }

    public static function sortItems($tree)
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
     * 获取所有父节点数据
     * @param mixed|integer $id
     * @return array
     */
    public static function getParents($id)
    {
        $parents = [];
        $row = Yii::$app->getDb()->createCommand('SELECT * FROM {{%category}} WHERE [[id]] = :id', [':id' => $id])->queryOne();
        $parents[] = $row;
        if ($row['parent_id']) {
            $parents = array_merge($parents, static::getParents($row['parent_id']));
        }

        return ArrayHelper::sortByCol($parents, 'parent_id');
    }

    /**
     * 判断是否有子节点
     * @param integer $id
     * @return boolean
     */
    private static function hasChildren($id)
    {
        $rawData = self::getRawItems();
        return isset($rawData[$id]) && $rawData[$id]['hasChildren'];

        return Yii::$app->getDb()->createCommand('SELECT COUNT(*) FROM {{%category}} WHERE parent_id = :parentId', [':parentId' => (int) $id])->queryScalar();
    }

    /**
     * 获取所有子节点数据
     * @param mixed|integer $parent
     * @return array
     */
    public static function getChildren($parent = 0)
    {
        $children = [];
        $parent = (int) $parent;
        $rawItems = self::getRawItems();
        if ($parent && isset($rawItems[$parent])) {
            foreach (self::getRawItemsByType($rawItems[$parent]['type']) as $item) {
                if ($item['hasChildren']) {
                    $children = array_merge(array_unshift($children, $item), static::getChildren($item['id']));
                }
            }
        }

        return $children;

//        $children = [];
//        $sql = 'SELECT * FROM {{%category}} WHERE [[tenant_id]] = :tenantId';
//        $bindValues = [':tenantId' => Yad::getTenantId()];
//        if ($parent) {
//            $sql.= ' AND [[parent_id]] = :parentId';
//            $bindValues[':parentId'] = $parent;
//        }
//        $rawData = Yii::$app->getDb()->createCommand($sql, $bindValues)->queryAll();
//        foreach ($rawData as $data) {
//            $children[] = $data;
//            if (static::hasChildren($data['id'])) {
//                $children = array_merge($children, static::getChildren($data['id']));
//            }
//        }
//
//        return $children;
    }

    /**
     * 获取所有子节点 id 集合
     * @param mixed|integer $parent
     * @return array
     */
    public static function getChildrenIds($parent = 0)
    {
        $childrenIds = [];
        $parent = (int) $parent;
        $rawItems = self::getRawItems();
        if ($parent && isset($rawItems[$parent])) {
            foreach (self::getRawItemsByType($rawItems[$parent]['type']) as $item) {
                if (self::hasChildren($item['id'])) {
                    $childrenIds = array_merge(array_unshift($childrenIds, $item['id']), static::getChildren($item['id']));
                }
            }
        }

        return $childrenIds;
    }

    // 事件
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (empty($this->alias) && !empty($this->name)) {
                $this->alias = Inflector::slug($this->name);
            }
            if ($this->parent_id && strpos($this->alias, '/') === false) {
                $parentAlias = Yii::$app->getDb()->createCommand('SELECT [[alias]] FROM {{%category}} WHERE [[id]] = :id', [':id' => $this->parent_id])->queryScalar();
                $this->alias = "{$parentAlias}/{$this->alias}";
            }

            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->generateCache();
    }

    public function afterDelete()
    {
        parent::afterDelete();
        $this->generateCache();
    }

}
