<?php

namespace app\widgets;

use yii\base\Widget;

class QQOnline extends Widget
{

    public function run()
    {
        $items = [
            '123456' => '业务部',
        ];

        return $this->render('QQOnline', [
                'items' => $items,
        ]);
    }

}
