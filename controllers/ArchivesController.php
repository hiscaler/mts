<?php

namespace app\controllers;

/**
 * 档案管理
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class ArchivesController extends Controller
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
        $data = \yadjet\mts\sdk\ArchiveGetter::one([
                'condition' => [
                    'id' => (int) $id,
                ]
        ]);
        if ($data === false) {
            
        }

        return $data;
    }

}
