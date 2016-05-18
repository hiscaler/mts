<?php

namespace app\models;

use PDO;
use Yii;
use yii\db\Query;
use yii\web\HttpException;

/**
 * This is the model class for table "tenant".
 *
 * @property integer $id
 * @property string $key
 * @property string $name
 * @property string $language
 * @property string $timezone
 * @property string $date_format
 * @property string $time_format
 * @property string $datetime_format
 * @property string $domain_name
 * @property integer $enabled
 * @property string $description
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_by
 * @property integer $updated_at
 */
class Tenant extends BaseActiveRecord
{

    private $_modules;
    public $modules;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tenant}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['name', 'language', 'timezone', 'domain_name'], 'required'],
            [['key', 'name', 'domain_name', 'description', 'date_format', 'time_format', 'datetime_format'], 'trim'],
            ['key', 'match', 'pattern' => '/^[1-9]{1}[0-9]{11}$/'],
            ['domain_name', 'match', 'pattern' => '/[a-zA-Z0-9][-a-zA-Z0-9]{1,62}(.[a-zA-Z0-9][-a-zA-Z0-9]{1,62})+.?/'],
            ['enabled', 'boolean'],
            [['key', 'name', 'domain_name', 'description'], 'string', 'max' => 255],
            [['language'], 'string', 'max' => 10],
            [['timezone', 'date_format', 'time_format', 'datetime_format', 'timezone', 'domain_name', 'description'], 'string', 'max' => 20],
            ['key', 'unique'],
            ['modules', 'safe'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'key' => Yii::t('tenant', 'Key'),
            'name' => Yii::t('tenant', 'Name'),
            'language' => Yii::t('tenant', 'Language'),
            'timezone' => Yii::t('tenant', 'Timezone'),
            'date_format' => Yii::t('tenant', 'Date Format'),
            'time_format' => Yii::t('tenant', 'Time Format'),
            'datetime_format' => Yii::t('tenant', 'Datetime Format'),
            'domain_name' => Yii::t('tenant', 'Domain Name'),
            'description' => Yii::t('tenant', 'Description'),
            'modules' => Yii::t('tenant', 'Modules'),
        ]);
    }

    /**
     * 管理用户
     * @return array
     */
    public function getUsers()
    {
        return (new Query())
                ->select(['u.id', 'u.username', 'u.nickname', 'u.email', 'u.status', 't.enabled', 't.role', 'wkr.name AS rule_name', 'tug.name AS group_name'])
                ->from('{{%tenant_user}} t')
                ->leftJoin('{{%user}} u', '[[t.user_id]] = [[u.id]]')
                ->leftJoin('{{%tenant_user_group}} tug', '[[t.user_group_id]] = [[tug.id]]')
                ->leftJoin('{{%workflow_rule}} wkr', '[[t.rule_id]] = [[wkr.id]]')
                ->where(['t.tenant_id' => $this->id])
                ->all();
    }

    /**
     * 获取租赁站点管理用户分组
     * @param integer $tenantId
     * @return array
     */
    public static function userGroups($tenantId = null)
    {
        if ($tenantId === null) {
            $tenantId = MTS::getTenantId();
        }
        $items = (new Query())
            ->select('name')
            ->from('{{%tenant_user_group}}')
            ->where([
                'tenant_id' => $tenantId === null ? MTS::getTenantId() : $tenantId,
                'enabled' => Option::BOOLEAN_TRUE
            ])
            ->orderBy(['alias' => SORT_ASC])
            ->indexBy('id')
            ->column();

        return $items;
    }

    /**
     * 获取租赁站点管理用户
     * @return array
     */
    public static function users()
    {
        $items = (new Query())
            ->select('u.username')
            ->from('{{%tenant_user}} t')
            ->leftJoin('{{%user}} u', '[[t.user_id]] = [[u.id]]')
            ->where([
                'tenant_id' => MTS::getTenantId(),
            ])
            ->orderBy(['u.username' => SORT_ASC])
            ->indexBy('u.id')
            ->column();

        return $items;
    }

    /**
     * 租赁站点定义的审核流程规则
     * @return array
     */
    public static function workflowRules($tenantId = null)
    {
        $items = (new Query())
            ->select('name')
            ->from('{{%workflow_rule}}')
            ->where([
                'tenant_id' => $tenantId === null ? MTS::getTenantId() : $tenantId
            ])
            ->indexBy('id')
            ->column();

        return $items;
    }

    /**
     * 租户可管理模块
     */
    public static function modules()
    {
        return Yii::$app->getDb()->createCommand('SELECT [[module_name]] FROM {{%tenant_module}} WHERE [[tenant_id]] = :tenantId')->bindValue(':tenantId', MTS::getTenantId(), PDO::PARAM_INT)->queryColumn();
    }

    // Events
    public function afterFind()
    {
        parent::afterFind();
        if ($this->isNewRecord) {
            $this->modules = [];
        } else {
            $this->modules = Yii::$app->db->createCommand('SELECT [[module_name]] FROM {{%tenant_module}} WHERE [[tenant_id]] = :tenantId')->bindValue(':tenantId', $this->id, PDO::PARAM_INT)->queryColumn();
        }
        $this->_modules = $this->modules;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $count = Yii::$app->db->createCommand('SELECT COUNT(*) FROM {{%tenant}}')->queryScalar();
                $this->key = date('Ymd') . sprintf('%04d', $count + 1);
            }

            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $modules = $this->modules;
        if (!is_array($this->_modules)) {
            $this->_modules = [];
        }
        if (!is_array($modules)) {
            $modules = [];
        }

        if ($insert) {
            Yii::$app->db->createCommand()->insert('{{%tenant_user}}', [
                'tenant_id' => $this->id,
                'user_id' => Yii::$app->user->id,
                'role' => User::ROLE_ADMINISTRATOR,
                'rule_id' => 0,
                'enabled' => Constant::BOOLEAN_TRUE,
                'user_group_id' => 0
            ])->execute();
            $insertModules = $modules;
            $deleteModules = [];
        } else {
            $insertModules = array_diff($modules, $this->_modules);
            $deleteModules = array_diff($this->_modules, $modules);
        }

        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            // Insert data
            if ($insertModules) {
                $rows = [];
                foreach ($insertModules as $moduleName) {
                    $rows[] = [$this->id, $moduleName];
                }
                $db->createCommand()->batchInsert('{{%tenant_module}}', [ 'tenant_id', 'module_name'], $rows)->execute();
            }
            // Delete data
            if ($deleteModules) {
                $db->createCommand()->delete('{{%tenant_module}}', [ 'tenant_id' => $this->id,
                    'module_name' => $deleteModules
                ])->execute();
            }
            $transaction->commit();
        } catch (HttpException $e) {
            $transaction->rollback();
            new HttpException('500', $e->getMessage());
        }
    }

}
