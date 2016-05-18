<?php

namespace app\models;

use app\modules\admin\extensions\ApplicationHelper;
use yadjet\behaviors\ImageUploadBehavior;
use Yii;

/**
 * This is the model class for table "{{%slide}}".
 *
 * @property integer $id
 * @property integer $group_id
 * @property string $title
 * @property string $url
 * @property string $url_open_target
 * @property string $picture
 * @property integer $enabled
 * @property integer $status
 * @property integer $ordering
 * @property integer $tenant_id
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_by
 * @property integer $updated_at
 * @property integer $deleted_at
 * @property integer $deleted_by
 */
class Slide extends BaseActiveRecord
{

    /**
     * 链接打开窗口
     */
    const URL_OPEN_TARGET_SELF = '_self';
    const URL_OPEN_TARGET_BLANK = '_blank';

    public $_fileUploadConfig;

    public function init()
    {
        $this->_fileUploadConfig = FileUploadConfig::getConfig(static::className2Id(), 'picture');
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%slide}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['title', 'enabled', 'status', 'ordering'], 'required'],
            [['title', 'url'], 'trim'],
            ['picture', 'required', 'on' => 'insert'],
            ['group_id', 'default', 'value' => 0],
            ['url', 'url'],
            ['enabled', 'default', 'value' => 0],
            [['group_id', 'status', 'ordering', 'deleted_at', 'deleted_by'], 'integer'],
            [['title', 'url', 'url_open_target'], 'string', 'max' => 255],
            ['picture', 'image',
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
            [
                'class' => ImageUploadBehavior::className(),
                'attribute' => 'picture',
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
            'url' => Yii::t('slide', 'URL'),
            'url_open_target' => Yii::t('slide', 'URL Open Target'),
            'picture' => Yii::t('slide', 'Picture'),
        ]);
    }

    public static function groupOptions()
    {
        return GroupOption::getItems('slide.group');
    }

    public static function urlOpenTargetOptions()
    {
        return [
            self::URL_OPEN_TARGET_SELF => Yii::t('slide', 'Self'),
            self::URL_OPEN_TARGET_BLANK => Yii::t('slide', 'Blank')
        ];
    }

    // Events
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            if ($this->isNewRecord) {
                $this->setScenario('insert');
            }

            return true;
        } else {
            return false;
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (empty($this->url)) {
                $this->url_open_target = null;
            } else {
                if (empty($this->url_open_target)) {
                    $this->url_open_target = static::URL_OPEN_TARGET_BLANK;
                }
            }

            return true;
        } else {
            return false;
        }
    }

}
