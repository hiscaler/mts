<?php

namespace app\modules\admin\widgets;

use Yii;
use yii\base\Widget;

class MyControlPanel extends Widget
{

    public $title;

    public function init()
    {
        parent::init();
        $this->title = Yii::t('app', 'Account Management');
    }

    public function getItems()
    {
        $controller = $this->view->context;
        $controllerId = $controller->id;
        $actionId = $controller->action->id;

        return [
            [
                'label' => Yii::t('app', 'Account Profile'),
                'url' => ['default/profile'],
                'active' => $controllerId == 'default' && $actionId == 'profile',
            ],
            [
                'label' => Yii::t('app', 'Change Password'),
                'url' => ['default/change-password'],
                'active' => $controllerId == 'default' && $actionId == 'change-password',
            ],
            [
                'label' => Yii::t('app', 'Login Logs'),
                'url' => ['default/login-logs'],
                'active' => $controllerId == 'default' && $actionId == 'login-logs',
            ],
        ];
    }

    public function run()
    {
        return $this->render('_controlPanel', [
                'items' => $this->getItems(),
        ]);
    }

}
