<?php

namespace app\modules\admin\controllers;

use app\models\Constant;
use app\models\FriendlyLink;
use app\models\FriendlyLinkSearch;
use app\models\MTS;
use app\models\Option;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * 友情链接管理
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class FriendlyLinksController extends ContentController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'undo', 'toggle'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'undo' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all FriendlyLink models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FriendlyLinkSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new FriendlyLink model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FriendlyLink();
        $model->url_open_target = FriendlyLink::URL_OPEN_TARGET_BLANK;
        $model->ordering = Constant::DEFAULT_ORDERING_VALUE;
        $model->enabled = Constant::BOOLEAN_TRUE;
        $model->status = Constant::STATUS_PUBLISHED;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing FriendlyLink model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing FriendlyLink model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $userId = Yii::$app->getUser()->getId();
        $now = time();
        Yii::$app->getDb()->createCommand()->update('{{%friendly_link}}', [
            'status' => Option::STATUS_DELETED,
            'updated_by' => $userId,
            'updated_at' => $now,
            'deleted_by' => $userId,
            'deleted_at' => $now
            ], ['id' => $model['id']])->execute();

        return $this->redirect(['index']);
    }

    /**
     * Undo delete an existing Special model.
     * If undo delete is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUndo($id)
    {
        $model = $this->findModel($id);
        Yii::$app->getDb()->createCommand()->update('{{%friendly_link}}', [
            'status' => Option::STATUS_PUBLISHED,
            'updated_by' => Yii::$app->getUser()->getId(),
            'updated_at' => time(),
            'deleted_by' => null,
            'deleted_at' => null,
            ], ['id' => $model['id']])->execute();

        return $this->redirect(['index']);
    }

    /**
     * 切换是否激活开关
     * @return Response
     */
    public function actionToggle()
    {
        $id = Yii::$app->request->post('id');
        $db = Yii::$app->getDb();
        $value = $db->createCommand('SELECT [[enabled]] FROM {{%friendly_link}} WHERE [[id]] = :id AND [[tenant_id]] = :tenantId')->bindValues([
                ':id' => (int) $id,
                ':tenantId' => MTS::getTenantId()
            ])->queryScalar();
        if ($value !== null) {
            $value = !$value;
            $now = time();
            $db->createCommand()->update('{{%friendly_link}}', ['enabled' => $value, 'updated_at' => $now, 'updated_by' => Yii::$app->getUser()->getId()], '[[id]] = :id', [':id' => (int) $id])->execute();
            $responseData = [
                'success' => true,
                'data' => [
                    'value' => $value,
                    'updatedAt' => Yii::$app->getFormatter()->asDate($now),
                    'updatedBy' => Yii::$app->getUser()->getIdentity()->username,
                ],
            ];
        } else {
            $responseData = [
                'success' => false,
                'error' => [
                    'message' => '数据有误',
                ],
            ];
        }

        return new Response([
            'format' => Response::FORMAT_JSON,
            'data' => $responseData,
        ]);
    }

    /**
     * Finds the FriendlyLink model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FriendlyLink the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = FriendlyLink::find()->where([
                'id' => (int) $id,
                'tenant_id' => MTS::getTenantId(),
            ])->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
