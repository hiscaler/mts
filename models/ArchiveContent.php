<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%archive_content}}".
 *
 * @property integer $id
 * @property integer $archive_id
 * @property string $content
 */
class ArchiveContent extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%archive_content}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['archive_id'], 'integer'],
            [['content'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('archive', 'ID'),
            'archive_id' => Yii::t('archive', 'Archive ID'),
            'content' => Yii::t('archive', 'Content'),
        ];
    }

}
