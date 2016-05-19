<?php

namespace app\controllers;

/**
 * 资讯
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class NewsController extends Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionList($node = null)
    {
        return $this->render('list', [
                'node' => $node,
        ]);
    }

    public function actionView($id)
    {
        $data = \yadjet\mts\sdk\ArchiveGetter::one($id);

        return $this->render('view', [
                'data' => $data
        ]);
    }

}
