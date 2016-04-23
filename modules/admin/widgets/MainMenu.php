<?php

namespace app\modules\admin\widgets;

use Yii;

class MainMenu extends \yii\base\Widget
{

    public function getItems()
    {
        $controller = $this->view->context;
        $controllerId = $controller->id;
        $items = [];
        $moduleId = Yii::$app->controller->module->id;


        $items[] = [
            'label' => Yii::t('app', 'Products Management'),
            'url' => ['/product/products/index'],
            'active' => $moduleId == 'product',
        ];
        $items[] = [
            'label' => Yii::t('app', 'Shop Management'),
            'url' => ['/shop/default/index'],
            'active' => $moduleId == 'shop',
        ];


        return $items;
    }

    public function run()
    {
        return $this->render('MainMenu', [
                'items' => $this->getItems(),
        ]);
    }

}
