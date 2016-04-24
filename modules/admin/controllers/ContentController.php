<?php

namespace app\modules\admin\controllers;

/**
 * 内容管理
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class ContentController extends Controller
{

    public $layout = 'content';

    public function actionIndex()
    {
        return $this->render('index');
    }

}
