<?php

namespace app\modules\admin\widgets;

use Yii;

class MainMenu extends \yii\base\Widget
{

    public function getItems()
    {
        $controllerId = $this->view->context->id;
        return [
            [
                'label' => Yii::t('app', 'System Management'),
                'url' => ['system/index'],
                'active' => in_array($controllerId, ['system', 'default', 'users', 'tenants', 'labels', 'nodes']),
            ],
            [
                'label' => Yii::t('app', 'Content Management'),
                'url' => ['content/index'],
                'active' => in_array($controllerId, ['content']),
            ],
        ];
    }

    public function run()
    {
        return $this->render('MainMenu', [
                'items' => $this->getItems(),
        ]);
    }

}
