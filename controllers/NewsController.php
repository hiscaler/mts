<?php

namespace app\controllers;

use Yii;

/**
 * 资讯
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class NewsController extends Controller
{

    public function actionIndex()
    {
        $request = Yii::$app->getRequest();
        if ($request->isAjax) {
            $items = [];
            $rawData = \yadjet\mts\sdk\ArchiveGetter::rows('id,node_id,title', ['node' => (int) $request->get('node'), ['orderBy' => 'id.desc'], 0, 10]);
            foreach ($rawData as $data) {
                $items[] = [
                    'url' => \yii\helpers\Url::toRoute(['news/view', 'node' => $data['node_id'], 'id' => $data['id']]),
                    'title' => $data['title'],
                ];
            }


            return new \yii\web\Response([
                'format' => \yii\web\Response::FORMAT_JSON,
                'data' => $items,
            ]);
        } else {
            return $this->render('index');
        }
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
