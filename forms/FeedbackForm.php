<?php

namespace app\forms;

use Yii;

class FeedbackForm extends \yii\base\Model
{

    public $username;
    public $tel;
    public $title;
    public $message;

    public function rules()
    {
        return [
            [['username', 'tel', 'title', 'message'], 'required'],
            [['username'], 'string', 'max' => 20],
            [['tel'], 'string', 'max' => 13],
            [['title'], 'string', 'max' => 100],
            [['message'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '姓名',
            'tel' => '电话',
            'title' => '标题',
            'message' => '咨询内容',
        ];
    }

}
