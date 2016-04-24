<?php

namespace app\models;

use Yii;

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
            'node_id' => Yii::t('archive', 'Node ID'),
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

    // Events
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->tenant_id = MTS::getTenantId();
            }

            $modelName = Yii::$app->getDb()->createCommand('SELECT [[model_name]] FROM {{%node}} WHERE [[id]] = :id', [':id' => $this->node_id])->queryScalar();
            $this->model_name = $modelName;

            return true;
        } else {
            return false;
        }
    }

}
