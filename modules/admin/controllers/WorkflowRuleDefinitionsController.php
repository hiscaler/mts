<?php

namespace app\modules\admin\controllers;

use backend\components\Controller;
use app\models\Option;
use app\models\WorkflowRule;
use app\models\WorkflowRuleDefinition;
use app\models\WorkflowRuleDefinitionSearch;
use app\models\Yad;
use PDO;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * WorkflowRuleDefinitionsController implements the CRUD actions for WorkflowRuleDefinition model.
 */
class WorkflowRuleDefinitionsController extends Controller {

    public function behaviors() {
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
                ],
            ],
        ];
    }

    /**
     * Lists all WorkflowRuleDefinition models.
     * @return mixed
     */
    public function actionIndex($ruleId) {
        $rule = $this->findRuleModel($ruleId);
        $searchModel = new WorkflowRuleDefinitionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'rule' => $rule,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new WorkflowRuleDefinition model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate($ruleId) {
        $rule = $this->findRuleModel($ruleId);
        $model = new WorkflowRuleDefinition();
        $model->rule_id = $rule->id;
        $model->enabled = Option::BOOLEAN_TRUE;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'ruleId' => $model->rule_id]);
        } else {
            return $this->render('create', [
                        'rule' => $rule,
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing WorkflowRuleDefinition model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'ruleId' => $model->rule_id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing WorkflowRuleDefinition model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);
        $exists = (new Query())->from('{{%workflow_task}}')->where(['definition_id' => $model->id])->exists();
        if ($exists) {
            $model->enabled = Option::BOOLEAN_FALSE;
            $model->save(false);
        } else {
            $model->delete();
        }

        return $this->redirect(['index', 'ruleId' => $model->rule_id]);
    }

    /**
     * Toggle enabled
     * @return Response
     */
    public function actionToggle() {
        $id = Yii::$app->request->post('id');
        $db = Yii::$app->getDb();
        $value = $db->createCommand('SELECT [[enabled]] FROM {{%workflow_rule_definition}} WHERE [[id]] = :id')->bindValue(':id', (int) $id, PDO::PARAM_INT)->queryScalar();
        if ($value !== null) {
            $value = !$value;
            $db->createCommand()->update('{{%workflow_rule_definition}}', ['enabled' => $value], '[[id]] = :id', [':id' => (int) $id])->execute();
            $responseData = [
                'success' => true,
                'data' => [
                    'value' => $value
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
     * Finds the WorkflowRuleDefinition model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WorkflowRuleDefinition the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        $model = WorkflowRuleDefinition::find()->where([
                    'id' => (int) $id,
                    'tenant_id' => Yad::getTenantId(),
                ])->one();
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findRuleModel($id) {
        if (($model = WorkflowRule::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
