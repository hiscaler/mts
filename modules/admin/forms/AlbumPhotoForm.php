<?php

namespace app\modules\admin\forms;

use Yii;
use yii\base\Model;

/**
 * 相册图片表单
 */
class AlbumPhotoForm extends Model
{

    public $title;
    public $photo_path;
    public $description;
    public $enabled;
    public $ordering;

    public function rules()
    {
        return [
            [['title', 'photo_path'], 'required'],
            [['title', 'photo_path'], 'string', 'max' => 255],
            [['ordering'], 'integer'],
            [['enabled'], 'boolean'],
            [['description'], 'string'],
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'title' => Yii::t('album', 'Title'),
            'photo_path' => Yii::t('album', 'Photo'),
        ];
    }

}
