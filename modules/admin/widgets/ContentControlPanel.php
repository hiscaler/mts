<?php

namespace app\modules\admin\widgets;

use common\models\BaseCode;
use app\models\Tenant;
use app\models\User;
use app\models\MTS;
use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

/**
 * 内容控制面板
 */
class ContentControlPanel extends Widget
{

    public $title;

    public function init()
    {
        parent::init();
        $this->title = Yii::t('app', 'Content Management');
    }

    public function getItems()
    {
        $items = [];
        $controller = $this->view->context;
        $controllerId = $controller->id;

        $items = [
            [
                'label' => Yii::t('app', 'Friendly Links'),
                'url' => ['friendly-links/index'],
                'active' => $controllerId == 'friendly-links',
            ],
        ];


        return $items;
    }

    public function run()
    {
        return $this->render('_controlPanel', [
                'items' => $this->getItems(),
        ]);
    }

}
