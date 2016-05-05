<?php

namespace app\models;

use PDO;
use Yii;
use yii\db\Query;

/**
 * This is the model class for table "{{%group_option}}".
 *
 * @property integer $id
 * @property string $group_name
 * @property string $text
 * @property string $value
 * @property string $alias
 * @property integer $enabled
 * @property integer $defaulted
 * @property integer $ordering
 * @property string $description
 * @property integer $tenant_id
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_by
 * @property integer $updated_at
 * @property integer $deleted_by
 * @property integer $deleted_at
 */
class GroupOption extends \yii\db\ActiveRecord
{
    
    use UserTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%group_option}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_name', 'text', 'value', 'ordering'], 'required'],
            ['group_name', 'match', 'pattern' => '/^[a-z][a-z.]+$/'],
            ['alias', 'match', 'pattern' => '/^[a-z-]+$/'],
            [['group_name', 'value'], 'unique', 'targetAttribute' => ['group_name', 'value', 'tenant_id']],
            [['alias'], 'unique', 'targetAttribute' => ['alias', 'tenant_id']],
            [['enabled', 'defaulted', 'ordering', 'tenant_id', 'created_by', 'created_at', 'updated_by', 'updated_at', 'deleted_by', 'deleted_at'], 'integer'],
            [['enabled', 'defaulted'], 'boolean'],
            [['group_name', 'text', 'value', 'alias', 'description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'group_name' => Yii::t('groupOption', 'Group Name'),
            'text' => Yii::t('groupOption', 'Text'),
            'value' => Yii::t('groupOption', 'Value'),
            'defaulted' => Yii::t('groupOption', 'Defaulted'),
            'description' => Yii::t('groupOption', 'Description'),
        ]);
    }

    /**
     * 获取分组名称
     * @return array
     */
    public static function getGroupNames()
    {
        $names = [];
        $rawData = (new Query)->select(['group_name'])->distinct(true)->from([static::tableName()])->where('tenant_id = :tenantId', [':tenantId' => MTS::getTenantId()])->column();
        foreach ($rawData as $data) {
            $names[$data] = $data;
        }

        return $names;
    }

    /**
     * Get all options
     * @return array
     */
    public static function getEntireOptions()
    {
        $options = [];
        $rawData = Yii::$app->getDb()->createCommand("SELECT [[group_name]], [[text]], [[value]], [[enabled]] FROM {{%group_option}} WHERE [[tenant_id]] = :tenantId ORDER BY [[group_name]], [[ordering]]")->bindValue(':tenantId', MTS::getTenantId(), PDO::PARAM_INT)->queryAll();
        foreach ($rawData as $data) {
            $options['groups'][$data['group_name']] = $data['group_name'];
            $options[$data['group_name']][$data['value']] = [$data['text'], $data['enabled']];
        }

        return $options;
    }

    /**
     * 获取指定的分组列表数据，没有传递 @groupName 参数时，将生成以 group_name 数据为键值的二维数组
     * @param string $groupName
     * @param string $defaultItemLabel 默认项目的文本，如果为 null 则不添加，否则添加 value = 0 的文本值
     * @param boolean $all 为 true 是返回所有数据，否则返回 enabled = 1 的数据
     * @return array
     */
    public static function getItems($groupName = null, $defaultItemLabel = null, $all = false)
    {
        $entireOptions = self::getEntireOptions();
        $items = [];
        if ($entireOptions) {
            if (!empty($groupName)) {
                if (isset($entireOptions[$groupName])) {
                    foreach ($entireOptions[$groupName] as $value => $text) {
                        if ($all) {
                            $items[$value] = $text[0];
                        } elseif ($text[1]) {
                            $items[$value] = $text[0];
                        }
                    }
                }
            } else {
                unset($entireOptions['groups']);
                foreach ($entireOptions as $k => $v) {
                    foreach ($v as $kk => $vv) {
                        if ($all) {
                            $items[$k][$kk] = $vv[0];
                        } elseif ($text[1]) {
                            $items[$k][$kk] = $vv[0];
                        }
                    }
                }
            }
        }
        if ($defaultItemLabel !== null) {
            if (!$items) {
                if (empty($defaultItemLabel)) {
                    $defaultItemLabel = '默认';
                }
                if (!empty($groupName)) {
                    array_unshift($items, $defaultItemLabel);
                } else {
                    foreach ($items as $key => $option) {
                        array_unshift($items[$key], $defaultItemLabel);
                    }
                }
            }
        }
        return $items;
    }

    /**
     * 根据分组名称和值获取对应的分组显示文本
     * @param string $groupName
     * @param string $value
     * @return mixed
     */
    public static function getText($groupName, $value)
    {
        $text = null;
        if (!empty($groupName) && !empty($value)) {
            $text = Yii::$app->getDb()->createCommand('SELECT [[text]] FROM {{%group_option}} WHERE [[tenant_id]] = :tenantId AND [[group_name]] = :groupName AND [[value]] = :value')->bindValues([
                    ':tenantId' => MTS::getTenantId(),
                    ':groupName' => trim($groupName),
                    ':value' => trim($value)
                ])->queryScalar();
        }

        return $text;
    }

    // Events
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if (!$insert && $this->defaulted) {
            Yii::$app->getDb()->createCommand()->update(self:: tableName(), ['defaulted' => Option:: BOOLEAN_FALSE], '[[group_name]] = :groupName AND [[id]] <> :id', [':groupName' => $this->group_name, ':id' => $this->id])->execute();
        }
    }

}
