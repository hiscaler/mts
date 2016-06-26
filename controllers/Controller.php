<?php

namespace app\controllers;

use Yii;

class Controller extends \yii\web\Controller
{

    public $layout = false;

    public function behaviors()
    {
        if (!YII_DEBUG) {
            $request = Yii::$app->getRequest();
            return [
                [
                    'class' => 'yii\filters\PageCache',
                    'only' => ['index', 'list', 'view'],
                    'duration' => 7200, // 2 hours
                    'variations' => [
                        $request->get('id'),
                        $request->get('node'),
                        $request->get('page'),
                        $request->get('alias'),
                    ]
                ],
            ];
        } else {
            return [];
        }
    }

}
