<?php

namespace app\modules\admin\widgets;

use app\models\BaseCode;
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
        $controllerId = $this->view->context->id;
        $modelName = Yii::$app->getRequest()->get('modelName');

        $items = [
            [
                'label' => Yii::t('app', 'Friendly Links'),
                'url' => ['friendly-links/index'],
                'active' => $controllerId == 'friendly-links',
            ],
            [
                'label' => Yii::t('app', 'Archives'),
                'url' => ['archives/index'],
                'active' => $controllerId == 'archives' && $modelName == null,
            ],
            [
                'label' => Yii::t('app', 'News'),
                'url' => ['archives/index', 'modelName' => 'news'],
                'active' => $controllerId == 'archives' && $modelName = 'news',
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
