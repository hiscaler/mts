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
                'active' => in_array($controllerId, ['system', 'default', 'users', 'tenants', 'labels', 'nodes', 'file-upload-configs', 'group-options', 'lookups']),
            ],
            [
                'label' => Yii::t('app', 'Content Management'),
                'url' => ['archives/index'],
                'active' => in_array($controllerId, ['content', 'archives', 'friendly-links', 'ad-spaces', 'ads', 'slides', 'articles']),
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
