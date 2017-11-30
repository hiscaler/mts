<?php

namespace app\controllers;

use Yii;
use yii\web\NotFoundHttpException;

class PageController extends Controller
{

    public function actionIndex($alias)
    {
        return $this->render('index', [
            'alias' => $alias,
            'data' => $this->findModel($alias),
        ]);
    }

    public function findModel($alias)
    {
        $data = Yii::$app->getDb()->createCommand('SELECT [[title]], [[content]] FROM {{%article}} WHERE [[alias]] = :alias AND tenant_id = :tenantId', [':alias' => trim($alias), ':tenantId' => $this->tenantId])->queryOne();
        if (!$data) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $data;
    }

}
