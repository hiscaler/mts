<?php

namespace app\modules\admin\widgets;

use Yii;

/**
 * 内容控制面板
 */
class ContentControlPanel extends \yii\base\Widget
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
                'label' => Yii::t('app', 'Archives'),
                'url' => ['archives/index'],
                'active' => $controllerId == 'archives',
                'items' => [
                    [
                        'label' => Yii::t('app', 'News'),
                        'url' => ['archives/index', 'modelName' => 'app-models-News'],
                        'active' => $controllerId == 'archives' && $modelName = 'news',
                    ]
                ]
            ],
            [
                'label' => Yii::t('app', 'Ad Spaces'),
                'url' => ['ad-spaces/index'],
                'active' => $controllerId == 'ad-spaces',
            ],
            [
                'label' => Yii::t('app', 'Ads'),
                'url' => ['ads/index'],
                'active' => $controllerId == 'ads',
            ],
            [
                'label' => Yii::t('app', 'Friendly Links'),
                'url' => ['friendly-links/index'],
                'active' => $controllerId == 'friendly-links',
            ],
            [
                'label' => Yii::t('app', 'Slides'),
                'url' => ['slides/index'],
                'active' => $controllerId == 'slides',
            ],
            [
                'label' => Yii::t('app', 'Articles'),
                'url' => ['articles/index'],
                'active' => $controllerId == 'articles',
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
