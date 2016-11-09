<?php

namespace app\widgets;

use Yii;

/**
 * 最近更新的资讯
 */
class LatestNews extends \yii\base\Widget
{

    public function run()
    {
        $items = Yii::$app->getDb()->createCommand('SELECT [[t.id]], [[t.title]], [[t.category_id]], [[c.name]] AS [[category_name]] FROM {{%news}} t LEFT JOIN {{%category}} c ON [[t.category_id]] = [[c.id]] WHERE [[t.enabled]] = :enabled AND [[t.category_id]] <> 9 ORDER BY [[t.published_at]] DESC LIMIT 16', [':enabled' => \app\models\Constant::BOOLEAN_TRUE])->queryAll();

        return $this->render('LatestNews', [
                'items' => $items,
        ]);
    }

}
