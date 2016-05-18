<?php

namespace app\models;

use app\modules\admin\extensions\ApplicationHelper;
use yadjet\behaviors\ImageUploadBehavior;
use Yii;
use yii\helpers\Inflector;

/**
 * This is the model class for table "{{%article}}".
 *
 * @property integer $id
 * @property string $alias
 * @property string $title
 * @property string $tags
 * @property string $keywords
 * @property string $description
 * @property string $content
 * @property string $picture_path
 * @property integer $ordering
 * @property integer $status
 * @property integer $enabled
 * @property integer $tenant_id
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_by
 * @property integer $updated_at
 * @property integer $deleted_by
 * @property integer $deleted_at
 */
class Article extends BaseActiveRecord
{

    public $_fileUploadConfig;

    public function init()
    {
        $this->_fileUploadConfig = FileUploadConfig::getConfig(static::className2Id(), 'picture_path');
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['title', 'content'], 'required'],
            [['alias', 'title', 'description'], 'trim'],
            ['alias', 'match', 'pattern' => '/^[a-z]+[.]?[a-z-]+[a-z]$/'],
            [['ordering', 'status', 'enabled', 'deleted_by', 'deleted_at'], 'integer'],
            [['alias', 'title'], 'string', 'max' => 255],
            [['description', 'content'], 'string'],
            ['ordering', 'default', 'value' => 0],
            [['picture_path'], 'safe'],
            ['alias', 'unique', 'targetAttribute' => ['alias', 'tenant_id']],
            ['picture_path', 'image',
                'extensions' => $this->_fileUploadConfig['extensions'],
                'minSize' => $this->_fileUploadConfig['size']['min'],
                'maxSize' => $this->_fileUploadConfig['size']['max'],
                'tooSmall' => Yii::t('app', 'The file "{file}" is too small. Its size cannot be smaller than {limit}.', [
                    'limit' => ApplicationHelper::friendlyFileSize($this->_fileUploadConfig['size']['min']),
                ]),
                'tooBig' => Yii::t('app', 'The file "{file}" is too big. Its size cannot exceed {limit}.', [
                    'limit' => ApplicationHelper::friendlyFileSize($this->_fileUploadConfig['size']['max']),
                ]),
            ],
        ]);
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
//            [
//                'class' => TaggedBehavior::className(),
//                'attribute' => 'tags',
//            ],
            [
                'class' => ImageUploadBehavior::className(),
                'attribute' => 'picture_path',
                'thumb' => $this->_fileUploadConfig['thumb']
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'picture_path' => Yii::t('app', 'Picture')
        ]);
    }

    // Events
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (empty($this->alias)) {
                $this->alias = Inflector::slug(Inflector::transliterate($this->title));
            }

            return true;
        } else {
            return false;
        }
    }

}
