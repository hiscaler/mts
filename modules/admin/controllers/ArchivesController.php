<?php

namespace app\modules\admin\controllers;

use app\models\Archive;
use app\models\ArchiveContent;
use app\models\ArchiveSearch;
use Yii;
use yii\base\Model;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * ArchivesController implements the CRUD actions for Archive model.
 */
class ArchivesController extends ContentController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Archive models.
     * @return mixed
     */
    public function actionIndex($modelName)
    {
        $searchModel = new ArchiveSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'modelName' => "\\app\\models\\" . $modelName,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Archive model.
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
     * Creates a new Archive model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($modelName)
    {
        $model = new Archive();
        $contentModel = new ArchiveContent();

        $post = Yii::$app->getRequest()->post();
        if ($model->load($post) && $contentModel->load($post) && Model::validateMultiple([$model, $contentModel])) {
            $db = Yii::$app->getDb();
            $transaction = $db->beginTransaction();
            try {
                $model->save();
                $model->saveContent($contentModel); // 保存正文内容
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollback();
                throw new HttpException(500, $e->getMessage());
            }
        } else {
            return $this->render('create', [
                    'modelName' => $modelName,
                    'model' => $model,
                    'contentModel' => $contentModel,
            ]);
        }
    }

    /**
     * Updates an existing Archive model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $contentModel = $model->content ? : new ArchiveContent();

        $post = Yii::$app->getRequest()->post();
        if ($model->load($post) && $contentModel->load($post) && Model::validateMultiple([$model, $contentModel])) {
            $db = Yii::$app->getDb();
            $transaction = $db->beginTransaction();
            try {
                $model->save();
                $model->saveContent($contentModel); // 保存正文内容
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollback();
                throw new HttpException(500, $e->getMessage());
            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                    'modelName' => $model->model_name,
                    'model' => $model,
                    'contentModel' => $contentModel,
            ]);
        }
    }

    /**
     * Deletes an existing Archive model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Archive model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Archive the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Archive::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
