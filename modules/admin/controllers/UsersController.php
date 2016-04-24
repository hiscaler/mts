<?php

namespace app\modules\admin\controllers;

use app\models\User;
use app\models\UserLoginLog;
use app\models\UserSearch;
use app\modules\admin\forms\ChangePasswordForm;
use app\modules\admin\forms\RegisterForm;
use Yii;
use yii\base\Security;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * 用户管理
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class UsersController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'view', 'delete', 'change-password'],
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        Url::remember(Yii::$app->getRequest()->getUrl());
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $loginLogsDataProvider = new ActiveDataProvider([
            'query' => UserLoginLog::find()->where(['user_id' => $model->id])->orderBy(['id' => SORT_DESC])
        ]);

        return $this->render('view', [
                'model' => $model,
                'loginLogsDataProvider' => $loginLogsDataProvider
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RegisterForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->password_hash = (new Security())->generatePasswordHash($model->password);
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
                'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
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
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = User::STATUS_DELETED;
        $model->deleted_by = Yii::app()->user->id;
        $model->deleted_at = time();
        $model->save(false);

        return $this->redirect(['index']);
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
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
