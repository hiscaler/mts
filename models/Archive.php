<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;

/**
 * This is the model class for table "{{%archive}}".
 *
 * @property integer $id
 * @property integer $node_id
 * @property string $model_name
 * @property string $title
 * @property string $keyword
 * @property string $description
 * @property string $tags
 * @property integer $has_thumbnail
 * @property string $thumbnail
 * @property string $author
 * @property string $source
 * @property integer $status
 * @property integer $enabled
 * @property integer $published_datetime
 * @property integer $clicks_count
 * @property integer $enabled_comment
 * @property integer $comments_count
 * @property integer $ordering
 * @property integer $tenant_id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 * @property integer $deleted_at
 * @property integer $deleted_by
 */
class Archive extends \yii\db\ActiveRecord
{

    use UserTrait;

    public $labels;
    private $_oldOwnerLabels = [];
    public $ownerLabels = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%archive}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['node_id', 'title', 'author', 'source', 'published_datetime'], 'required'],
            [['node_id', 'has_thumbnail', 'status', 'published_datetime', 'clicks_count', 'comments_count', 'ordering', 'tenant_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['enabled', 'enabled_comment'], 'boolean'],
            [['enabled'], 'default', 'value' => Constant::BOOLEAN_TRUE],
            [['enabled_comment'], 'default', 'value' => Constant::BOOLEAN_FALSE],
            [['status'], 'default', 'value' => Constant::STATUS_PUBLISHED],
            [['description'], 'string'],
            [['model_name', 'source'], 'string', 'max' => 30],
            [['title', 'keyword'], 'string', 'max' => 255],
            [['tags'], 'string', 'max' => 200],
            [['thumbnail'], 'string', 'max' => 100],
            [['author'], 'string', 'max' => 20],
            [['ownerLabels'], 'safe'],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
            ],
            [
                'class' => \yii\behaviors\BlameableBehavior::className()
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('archive', 'ID'),
            'node_id' => Yii::t('archive', 'Node'),
            'model_name' => Yii::t('archive', 'Model Name'),
            'title' => Yii::t('archive', 'Title'),
            'keyword' => Yii::t('archive', 'Keyword'),
            'description' => Yii::t('archive', 'Description'),
            'tags' => Yii::t('archive', 'Tags'),
            'has_thumbnail' => Yii::t('archive', 'Has Thumbnail'),
            'thumbnail' => Yii::t('archive', 'Thumbnail'),
            'author' => Yii::t('archive', 'Author'),
            'source' => Yii::t('archive', 'Source'),
            'status' => Yii::t('archive', 'Status'),
            'enabled' => Yii::t('archive', 'Enabled'),
            'published_datetime' => Yii::t('archive', 'Published Datetime'),
            'clicks_count' => Yii::t('archive', 'Clicks Count'),
            'enabled_comment' => Yii::t('archive', 'Enabled Comment'),
            'comments_count' => Yii::t('archive', 'Comments Count'),
            'ordering' => Yii::t('archive', 'Ordering'),
            'tenant_id' => Yii::t('archive', 'Tenant ID'),
            'created_at' => Yii::t('archive', 'Created At'),
            'created_by' => Yii::t('archive', 'Created By'),
            'updated_at' => Yii::t('archive', 'Updated At'),
            'updated_by' => Yii::t('archive', 'Updated By'),
            'deleted_at' => Yii::t('archive', 'Deleted At'),
            'deleted_by' => Yii::t('archive', 'Deleted By'),
        ];
    }

    /**
     * 正文
     * @return ActiveRecord
     */
    public function getContent()
    {
        return $this->hasOne(ArchiveContent::className(), ['archive_id' => 'id']);
    }

    /**
     * 自定义属性
     * @return ActiveRecord
     */
    public function getCustomeLabels()
    {
        return $this->hasMany(Label::className(), ['id' => 'label_id'])
                ->select(['id', 'name'])
                ->viaTable('{{%arvhive_label}}', ['archive_id' => 'id'], function ($query) {
                    $query->where(['model_name' => static::className2Id()]);
                }
        );
    }

    /**
     * 保存正文内容
     * @param ActiveReocrd $archiveContent
     * @return boolean
     */
    public function saveContent($archiveContent)
    {
        $archiveContent->archive_id = $this->id;
        return $archiveContent->save();
    }

    // Events
    public function afterFind()
    {
        parent::afterFind();
        if (!$this->isNewRecord) {
            $this->ownerLabels = Label::getArchiveLabelIds($this->id, static::className2Id());
            $this->_oldOwnerLabels = $this->ownerLabels;
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->tenant_id = MTS::getTenantId();
            }

            $this->has_thumbnail = !empty($this->thumbnail) ? Constant::BOOLEAN_TRUE : Constant::BOOLEAN_FALSE;
            $modelName = Yii::$app->getDb()->createCommand('SELECT [[model_name]] FROM {{%node}} WHERE [[id]] = :id', [':id' => $this->node_id])->queryScalar();
            $this->model_name = $modelName;

            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        // Entity attributes
        $ownerLabels = $this->ownerLabels;
        if (!is_array($this->_oldOwnerLabels)) {
            $this->_oldOwnerLabels = [];
        }
        if (!is_array($ownerLabels)) {
            $ownerLabels = [];
        }

        if ($insert) {
            $insertLabels = $ownerLabels;
            $deleteLabels = [];
        } else {
            $insertLabels = array_diff($ownerLabels, $this->_oldOwnerLabels);
            $deleteLabels = array_diff($this->_oldOwnerLabels, $ownerLabels);
        }

        $db = Yii::$app->getDb();
        $transaction = $db->beginTransaction();
        try {
            // Insert data
            if ($insertLabels) {
                $rows = [];
                $tenantId = MTS::getTenantId();
                foreach ($insertLabels as $attributeId) {
                    $rows[] = [$this->id, static::className2Id(), $attributeId, $tenantId];
                }
                if ($rows) {
                    $db->createCommand()->batchInsert('{{%archive_label}}', ['archive_id', 'model_name', 'label_id', 'tenant_id'], $rows)->execute();
                    $db->createCommand("UPDATE {{%label}} SET [[frequency]] = [[frequency]] + 1 WHERE [[id]] IN (" . implode(', ', ArrayHelper::getColumn($rows, 2)) . ")")->execute();
                }
            }
            // Delete data
            if ($deleteLabels) {
                $db->createCommand()->delete('{{%archive_label}}', [
                    'archive_id' => $this->id,
                    'model_name' => static::className2Id(),
                    'label_id' => $deleteLabels
                ])->execute();
                $db->createCommand("UPDATE {{%label}} SET [[frequency]] = [[frequency]] - 1 WHERE [[id]] IN (" . implode(', ', $deleteLabels) . ")")->execute();
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();
            throw new HttpException('500', $e->getMessage());
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();
        \Yii::$app->getDb()->delete('{{%archive_content}}', ['archive_id' => $this->id])->execute();
    }

}
