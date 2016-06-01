<?php

namespace app\modules\admin;

class Module extends \yii\base\Module
{

    public $controllerNamespace = 'app\modules\admin\controllers';

    public function init()
    {
        
        parent::init();
        \Yii::$app->setComponents([
            'formatter' => [
                'class' => 'app\modules\admin\extensions\Formatter',
            ],
            'errorHandler' => [
                'class' => 'yii\web\ErrorHandler',
                'errorAction' => '/admin/default/error',
            ],
            'user' => [
                'class' => 'yii\web\User',
                'identityClass' => 'app\models\User',
                'enableAutoLogin' => true,
                'loginUrl' => 'login'
            ],
        ]);
    }

}
