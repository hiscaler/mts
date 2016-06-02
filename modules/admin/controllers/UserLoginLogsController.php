<?php

namespace app\modules\admin\controllers;

use app\models\UserLoginLogSearch;
use Yii;
use yii\filters\AccessControl;

/**
 * 会员登录日志
 */
class UserLoginLogsController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all UserLoginLog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserLoginLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

}
