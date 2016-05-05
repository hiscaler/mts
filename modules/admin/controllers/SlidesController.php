<?php

namespace app\modules\admin\controllers;

use app\models\Constant;
use app\models\MTS;
use app\models\Option;
use app\models\Slide;
use app\models\SlideSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * SlidesController implements the CRUD actions for Slide model.
 */
class SlidesController extends ContentController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'undo', 'toggle'],
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
                    'toggle' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Slide models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SlideSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Slide model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
                'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Slide model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Slide();
        $model->enabled = Constant::BOOLEAN_TRUE;
        $model->ordering = Constant::DEFAULT_ORDERING_VALUE;
        $model->status = Constant::STATUS_PUBLISHED;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Slide model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Slide model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $userId = Yii::$app->getUser()->getId();
        $now = time();
        Yii::$app->getDb()->createCommand()->update('{{%slide}}', [
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
        Yii::$app->getDb()->createCommand()->update('{{%slide}}', [
            'status' => Option::STATUS_PUBLISHED,
            'updated_by' => Yii::$app->getUser()->getId(),
            'updated_at' => time(),
            'deleted_by' => null,
            'deleted_at' => null,
            ], ['id' => $model['id']])->execute();

        return $this->redirect(['index']);
    }

    public function actionToggle()
    {
        $id = Yii::$app->request->post('id');
        $db = Yii::$app->getDb();
        $value = $db->createCommand('SELECT [[enabled]] FROM {{%slide}} WHERE [[id]] = :id AND [[tenant_id]] = :tenantId')->bindValues([
                ':id' => (int) $id,
                ':tenantId' => MTS::getTenantId()
            ])->queryScalar();
        if ($value !== null) {
            $value = !$value;
            $db->createCommand()->update('{{%slide}}', ['enabled' => $value, 'updated_at' => time()], '[[id]] = :id', [':id' => (int) $id])->execute();
            $responseData = [
                'success' => true,
                'data' => [
                    'value' => $value,
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
     * Finds the Slide model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Slide the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Slide::find()->where([
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
