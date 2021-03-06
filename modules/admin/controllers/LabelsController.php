<?php

namespace app\modules\admin\controllers;

use app\models\Constant;
use app\models\Label;
use app\models\LabelSearch;
use app\models\Yad;
use PDO;
use Yii;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * 推送位管理
 *
 * @author hiscaler <hiscaler@gmail.com>
 */
class LabelsController extends GlobalController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'toggle'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'toggle' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Label models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LabelSearch();
        $dataProvider = $searchModel->search(Yii::$app->getRequest()->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Label model.
     * If creation is successful, the browser will be redirected to the 'create' page.
     *
     * @return mixed
     */
    public function actionCreate($ordering = 1)
    {
        $model = new Label();
        $model->enabled = Constant::BOOLEAN_TRUE;
        $model->ordering = (int) $ordering;
        $model->loadDefaultValues();

        if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
            return $this->redirect(['create', 'ordering' => $model->ordering + 1]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Label model.
     * If update is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Label model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->frequency) {
            throw new NotAcceptableHttpException('The requested does not acceptable.');
        } else {
            $db = Yii::$app->getDb();
            $transaction = $db->beginTransaction();
            try {
                $db->createCommand('DELETE FROM {{%label}} WHERE id = :id', [':id' => (int) $id])->execute();
                $db->createCommand('DELETE FROM {{%entity_label}} WHERE label_id = :labelId', [':labelId' => (int) $id])->execute();
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
                throw new Exception($e->getMessage());
            }

            return $this->redirect(['index']);
        }
    }

    /**
     * 激活禁止操作
     *
     * @return Response
     */
    public function actionToggle()
    {
        $id = Yii::$app->getRequest()->post('id');
        $db = Yii::$app->getDb();
        $command = $db->createCommand('SELECT [[enabled]] FROM {{%label}} WHERE [[id]] = :id AND [[tenant_id]] = :tenantId');
        $command->bindValues([
            ':id' => (int) $id,
            ':tenantId' => Yad::getTenantId(),
        ]);
        $command->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $value = $command->queryScalar();
        if ($value !== null) {
            $value = !$value;
            $now = time();
            $db->createCommand()->update('{{%label}}', ['enabled' => $value, 'updated_at' => $now, 'updated_by' => Yii::$app->getUser()->getId()], '[[id]] = :id', [':id' => (int) $id])->execute();
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
     * Finds the Label model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Label the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Label::find()->where([
            'id' => (int) $id,
            'tenant_id' => Yad::getTenantId(),
        ])->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
