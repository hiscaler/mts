<?php

namespace app\controllers;

use app\models\Constant;
use Yii;
use yii\data\Pagination;
use yii\db\Query;
use yii\web\NotFoundHttpException;

/**
 * 资讯管理
 *
 * @author hiscaler <hiscaler@gmail.com>
 */
class NewsController extends Controller
{

    const PAGE_SIZE = 8;

    public function actionIndex($category)
    {
        $where = [
            'category_id' => (int) $category,
        ];
        $categoryName = Yii::$app->getDb()->createCommand('SELECT [[name]] FROM {{%category}} WHERE id = :id', [':id' => (int) $category])->queryScalar();
        $query = (new Query())
            ->select(['id', 'category_id', 'title', 'description', 'published_at'])
            ->from('{{%news}} t')
            ->where($where);
        $pagination = new Pagination(['totalCount' => $query->count(), 'pageSize' => self::PAGE_SIZE]);

        $items = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy(['t.id' => SORT_DESC])
            ->all();

        return $this->render('index', [
            'categoryName' => $categoryName,
            'items' => $items,
            'pagination' => $pagination,
        ]);
    }

    public function actionView($id)
    {
        $data = (new Query())
            ->select(['t.*', 'cate.name AS category_name', 'c.content'])
            ->from('{{%news}} t')
            ->leftJoin('{{%news_content}} c', '[[t.id]] = [[c.news_id]]')
            ->leftJoin('{{%category}} cate', '[[t.category_id]] = [[cate.id]]')
            ->where([
                't.enabled' => Constant::BOOLEAN_TRUE,
                't.id' => (int) $id
            ])
            ->one();

        if ($data) {
            Yii::$app->getDb()->createCommand('UPDATE {{%news}} SET [[clicks_count]] = [[clicks_count]] + 1 WHERE [[id]] = :id', array(':id' => $data['id']))->execute();

            return $this->render('view', [
                'data' => $data,
            ]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
