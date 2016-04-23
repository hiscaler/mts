<?php

namespace app\modules\admin\controllers;

use app\models\Constant;
use app\models\Node;
use app\models\NodeSearch;
use app\models\Option;
use app\models\MTS;
use yadjet\helpers\ArrayHelper;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * 节点管理
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class NodesController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'toggle', 'choice', 'toggle-entity-enabled'],
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
                    'toggle-entity-enabled' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Node models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NodeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Node model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($parentId = null, $modelName = null, $ordering = 0, $entityStatus = null, $entityEnabled = null)
    {
        $model = new Node();
        $model->enabled = Constant::BOOLEAN_TRUE;
        if ($parentId) {
            $model->parent_id = (int) $parentId;
        }
        if ($modelName) {
            $model->model_name = $modelName;
        }
        if ($ordering) {
            $model->ordering = (int) $ordering;
        }
        if ($entityStatus) {
            $model->entity_status = (int) $entityStatus;
        } elseif (is_null($entityStatus)) {
            $model->entity_status = Constant::STATUS_PUBLISHED;
        }
        if ($entityEnabled) {
            $model->entity_enabled = (int) $entityEnabled;
        } elseif (is_null($entityEnabled)) {
            $model->entity_enabled = Constant::BOOLEAN_TRUE;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['create', 'parentId' => $model->parent_id, 'modelName' => $model->model_name, 'ordering' => $model->ordering + 1, 'entityStatus' => $model->entity_status, 'entityEnabled' => $model->entity_enabled]);
        } else {
            return $this->render('create', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Node model.
     * If update is successful, the browser will be redirected to the 'view' page.
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
     * Deletes an existing Node model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $toDelete = false;
        if ($model->direct_data_count == 0 && $model->relation_data_count == 0) {
            $children = Node::getChildren($id);
            if (!$children) {
                $toDelete = true;
            }
        }
        if ($toDelete) {
            $model->delete();
        } else {
            throw new NotAcceptableHttpException('该节点包含有子节点或者有关联数据，禁止删除');
        }

        return $this->redirect(['index']);
    }

    /**
     * 激活、禁止记录 enabled 状态
     * @return Response
     */
    public function actionToggle()
    {
        $id = Yii::$app->request->post('id');
        $db = Yii::$app->getDb();
        $command = $db->createCommand('SELECT [[enabled]] FROM {{%node}} WHERE [[id]] = :id AND [[tenant_id]] = :tenantId');
        $command->bindValues([
            ':id' => (int) $id,
            ':tenantId' => MTS::getTenantId()
        ]);
        $value = $command->queryScalar();
        if ($value !== null) {
            $value = !$value;
            $now = time();
            $db->createCommand()->update('{{%node}}', ['enabled' => $value, 'updated_at' => $now, 'updated_by' => Yii::$app->getUser()->getId()], '[[id]] = :id', [':id' => (int) $id])->execute();
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
     * 激活、禁止记录 entity_enabled 状态
     * @return Response
     */
    public function actionToggleEntityEnabled()
    {
        $id = Yii::$app->request->post('id');
        $db = Yii::$app->getDb();
        $command = $db->createCommand('SELECT [[entity_enabled]] FROM {{%node}} WHERE [[id]] = :id AND [[tenant_id]] = :tenantId');
        $command->bindValues([
            ':id' => (int) $id,
            ':tenantId' => MTS::getTenantId()
        ]);
        $value = $command->queryScalar();
        if ($value !== null) {
            $value = !$value;
            $now = time();
            $db->createCommand()->update('{{%node}}', ['entity_enabled' => $value, 'updated_at' => $now, 'updated_by' => Yii::$app->getUser()->getId()], '[[id]] = :id', [':id' => (int) $id])->execute();
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

    public function actionChoice()
    {
        $nodeIds = Yii::$app->request->get('nodeIds');
        $nodeIds = !empty($nodeIds) ? explode(',', $nodeIds) : [];
        $nodes = Yii::$app->getDb()->createCommand('SELECT [[id]], [[parent_id]] AS [[pId]], [[name]] FROM {{%node}} WHERE [[tenant_id]] = :tenantId')->bindValue(':tenantId', MTS::getTenantId(), \PDO::PARAM_INT)->queryAll();
        if ($nodeIds) {
            foreach ($nodes as $key => $node) {
                if (in_array($node['id'], $nodeIds)) {
                    $nodes[$key]['checked'] = true;
                }
            }
        }
        $nodes = ArrayHelper::toTree($nodes, 'id', 'pId');

        return $this->renderAjax('choice', [
                'data' => array_values($nodes),
        ]);
    }

    /**
     * Finds the Node model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Node the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Node::find()->where([
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
