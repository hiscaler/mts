<?php

namespace app\controllers;

/**
 * 单文章
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class PageController extends Controller
{

    public function actionIndex($alias)
    {
        $data = \yadjet\mts\sdk\ArticleGetter::one($alias);
        if (!$data) {
            throw new \yii\web\NotFoundHttpException('内容不存在。');
        }

        return $this->render('index', [
                'data' => $data,
        ]);
    }

}
