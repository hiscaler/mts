<?php

namespace app\modules\admin\controllers;

/**
 * 系统管理
 *
 * @author hiscaler <hiscaler@gmail.com>
 */
class SystemController extends GlobalController
{

    public function actionIndex()
    {
        return $this->render('index');
    }

}
