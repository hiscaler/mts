<?php

namespace app\modules\admin\controllers;

use app\models\MTS;
use app\models\Option;
use app\models\TenantUser;
use app\models\TenantUserSearch;
use app\models\User;
use app\modules\admin\forms\ChangePasswordForm;
use app\modules\admin\forms\CreateTenantUserForm;
use app\modules\admin\forms\RegisterForm;
use PDO;
use yadjet\helpers\ArrayHelper;
use Yii;
use yii\base\Security;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * UserController implements the CRUD actions for User model.
 */
class TenantUsersController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'auth', 'create-tenant-user', 'toggle', 'change-password'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'toggle' => ['post'],
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        Url::remember(Yii::$app->getRequest()->getUrl());
        $searchModel = new TenantUserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RegisterForm();
        $model->type = User::TYPE_BACKEND;
        $model->role = User::ROLE_USER;
        $model->status = User::STATUS_ACTIVE;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->password_hash = (new Security())->generatePasswordHash($model->password);
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
                'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findTenantUserModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if ((int) $id == Yii::$app->getUser()->getId()) {
            throw new BadRequestHttpException("Can't remove itself.");
        }

        $model = $this->findModel($id);
        $userId = $model->id;
        $db = Yii::$app->getDb();
        $db->transaction(function ($db) use ($userId) {
            $tenantId = MTS::getTenantId();
            $bindValues = [
                ':tenantId' => $tenantId,
                ':userId' => $userId
            ];
            $db->createCommand()->delete('{{%tenant_user}}', '[[tenant_id]] = :tenantId AND [[user_id]] = :userId', $bindValues)->execute();
            $db->createCommand('DELETE FROM {{%user_auth_node}} WHERE [[user_id]] = :userId AND [[node_id]] IN (SELECT [[id]] FROM {{%node}} WHERE [[tenant_id]] = :tenantId)')->bindValues($bindValues)->execute();
        });

        return $this->redirect(['index']);
    }

    /**
     * 用户节点权限控制
     * @param integer $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionAuth($id)
    {
        $userId = (int) $id;
        $tenantId = MTS::getTenantId();
        $db = Yii::$app->getDb();
        $userExists = $db->createCommand('SELECT COUNT(*) FROM {{%user}} WHERE [[id]] = :id AND [[id]] IN (SELECT [[user_id]] FROM {{%tenant_user}} WHERE [[tenant_id]] = :tenantId)')->bindValues([
                ':id' => $userId,
                ':tenantId' => $tenantId
            ])->queryScalar();
        if (!$userExists) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $existingNodeIds = $db->createCommand('SELECT [[node_id]] FROM {{%user_auth_node}} WHERE [[user_id]] = :userId AND [[node_id]] IN (SELECT [[id]] FROM {{%node}} WHERE [[tenant_id]] = :tenantId)')->bindValues([
                ':userId' => $userId,
                ':tenantId' => $tenantId
            ])->queryColumn();
        $request = Yii::$app->getRequest();
        if ($request->isPost && $request->isAjax) {
            $choiceNodeIds = $request->post('choiceNodeIds');
            if (!empty($choiceNodeIds)) {
                $choiceNodeIds = explode(',', $choiceNodeIds);
                $insertNodeIds = array_diff($choiceNodeIds, $existingNodeIds);
                $deleteNodeIds = array_diff($existingNodeIds, $choiceNodeIds);
            } else {
                $insertNodeIds = [];
                $deleteNodeIds = $existingNodeIds; // 如果没有选择任何节点，表示删除所有已经存在节点
            }

            if ($insertNodeIds || $deleteNodeIds) {
                $transaction = $db->beginTransaction();
                try {
                    if ($insertNodeIds) {
                        $insertRows = [];
                        foreach ($insertNodeIds as $nodeId) {
                            $insertRows[] = [$userId, $nodeId];
                        }
                        if ($insertRows) {
                            $db->createCommand()->batchInsert('{{%user_auth_node}}', ['user_id', 'node_id'], $insertRows)->execute();
                        }
                    }
                    if ($deleteNodeIds) {
                        $db->createCommand()->delete('{{%user_auth_node}}', [
                            'user_id' => $userId,
                            'node_id' => $deleteNodeIds
                        ])->execute();
                    }
                    $transaction->commit();
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    return new Response([
                        'format' => Response::FORMAT_JSON,
                        'data' => [
                            'success' => false,
                            'error' => [
                                'message' => $e->getMessage()
                            ]
                        ],
                    ]);
                }
            }

            return new Response([
                'format' => Response::FORMAT_JSON,
                'data' => [
                    'success' => true
                ],
            ]);
        }

        $nodes = $db->createCommand('SELECT [[id]], [[parent_id]] AS [[pId]], [[name]] FROM {{%node}} WHERE [[tenant_id]] = :tenantId')->bindValue(':tenantId', $tenantId, PDO::PARAM_INT)->queryAll();
        if ($existingNodeIds) {
            foreach ($nodes as $key => $node) {
                if (in_array($node['id'], $existingNodeIds)) {
                    $nodes[$key]['checked'] = true;
                }
            }
        }
        $nodes = ArrayHelper::toTree($nodes, 'id', 'pId');

        return $this->renderAjax('auth', [
                'nodes' => $nodes,
        ]);
    }

    /**
     * 添加站点管理用户
     * @return mixed
     */
    public function actionCreateTenantUser()
    {
        $model = new CreateTenantUserForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            Yii::$app->getDb()->createCommand()->insert('{{%tenant_user}}', [
                'tenant_id' => MTS::getTenantId(),
                'user_id' => $model->user_id,
                'role' => $model->role,
                'rule_id' => $model->rule_id,
                'enabled' => Option::BOOLEAN_TRUE,
                'user_group_id' => $model->user_group_id
            ])->execute();
            Yii::$app->getSession()->setFlash('notice', "用户 {$model->username} 已经成功绑定「" . MTS::getTenantName() . "」站点。");
            return $this->redirect('index');
        }

        return $this->render('createTenantUser', [
                'model' => $model,
        ]);
    }

    /**
     * 切换是否激活开关
     * @return Response
     */
    public function actionToggle()
    {
        $userId = (int) Yii::$app->request->post('id');
        $tenantId = MTS::getTenantId();
        $db = Yii::$app->getDb();
        $value = $db->createCommand('SELECT [[enabled]] FROM {{%tenant_user}} WHERE [[user_id]] = :id AND [[tenant_id]] = :tenantId')->bindValues([
                ':id' => $userId,
                ':tenantId' => $tenantId
            ])->queryScalar();
        if ($value !== null) {
            $value = !$value;
            $db->createCommand()->update('{{%tenant_user}}', ['enabled' => $value], '[[user_id]] = :id AND [[tenant_id]] = :tenantId', [':id' => $userId, ':tenantId' => $tenantId])->execute();
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
     * Change user password
     * @return mixed
     */
    public function actionChangePassword($id)
    {
        $user = $this->findModel($id);
        $model = new ChangePasswordForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user->setPassword($model->password);
            if ($user->save(false)) {
                Yii::$app->getDb()->createCommand('UPDATE {{%user}} SET [[last_change_password_time]] = :now WHERE [[id]] = :id', [':now' => time(), ':id' => $user->id])->execute();
                Yii::$app->getSession()->setFlash('notice', "用户 {$user->username} 密码修改成功，请通知用户下次登录使用新的密码。");
                return $this->redirect(Url::previous());
            }
        }

        return $this->render('changePassword', [
                'user' => $user,
                'model' => $model,
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = User::find()->where([
                'id' => (int) $id,
            ])
            ->andWhere('[[id]] IN (SELECT [[user_id]] FROM {{%tenant_user}} WHERE [[tenant_id]] = :tenantId)', [':tenantId' => MTS::getTenantId()])
            ->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findTenantUserModel($id)
    {
        $model = TenantUser::find()->where([
                'user_id' => (int) $id,
                'tenant_id' => MTS::getTenantId()
            ])
            ->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
