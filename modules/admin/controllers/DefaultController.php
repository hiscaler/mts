<?php

namespace app\modules\admin\controllers;

use app\forms\ChangeMyPasswordForm;
use app\forms\LoginForm;
use app\models\Option;
use app\models\User;
use app\models\MTS;
use PDO;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class DefaultController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'captcha'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'profile', 'change-password', 'login-logs', 'choice-tenant', 'change-tenant'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'transparent' => true,
                'offset' => 2,
                'padding' => 0,
                'height' => 32
            ],
        ];
    }

    public function actionIndex()
    {
        if (!MTS::getTenantId()) {
            $this->layout = 'base';
        }

        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!Yii::$app->getUser()->isGuest) {
            return $this->goHome();
        }
        $this->layout = false;

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $tenantIds = Yii::$app->getDb()->createCommand('SELECT [[tenant_id]] FROM {{%tenant_user}} WHERE [[user_id]] = :userId AND [[enabled]] = :enabled')->bindValues([
                    ':userId' => Yii::$app->getUser()->getId(),
                    ':enabled' => Option::BOOLEAN_TRUE
                ])->queryColumn();
            if (count($tenantIds) == 1) {
                MTS::setTenantData($tenantIds[0]);
                $url = ['default/index'];
            } else {
                $url = ['default/choice-tenant'];
            }

            return $this->redirect($url);
        } else {
            return $this->render('login', [
                    'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->getUser()->logout();
        // 清理 COOKIE 信息
        Yii::$app->getResponse()->getCookies()->remove('_tenant');

        return $this->goHome();
    }

    /**
     * 修改帐号资料
     * @return mixed
     */
    public function actionProfile()
    {
        $this->layout = 'my';
        $model = $this->findCurrentUserModel();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'User profile save successed.'));
            return $this->redirect(['profile']);
        } else {
            return $this->render('profile', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * Change current logined user password
     * @return mixed
     */
    public function actionChangePassword()
    {
        $this->layout = 'my';
        $user = $this->findCurrentUserModel();
        $model = new ChangeMyPasswordForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user->setPassword($model->password);
            if ($user->save(false)) {
                Yii::$app->getDb()->createCommand('UPDATE {{%user}} SET [[last_change_password_time]] = :now WHERE [[id]] = :id', [':now' => time(), ':id' => $user->id])->execute();

                Yii::$app->getSession()->setFlash('notice', "您的密码修改成功，请下次登录使用新的密码。");
                return $this->redirect(Url::previous());
            }
        }

        return $this->render('changePassword', [
                'user' => $user,
                'model' => $model,
        ]);
    }

    /**
     * 用户登录日志
     * @return mixed
     */
    public function actionLoginLogs()
    {
        $this->layout = 'my';
        $loginLogs = [];
        $formatter = Yii::$app->getFormatter();
        $rawData = Yii::$app->getDb()->createCommand('SELECT [[t.login_ip]], [[t.client_informations]], [[t.login_at]] FROM {{%user_login_log}} t WHERE [[t.user_id]] = :userId ORDER BY [[t.login_at]] DESC')->bindValue(':userId', Yii::$app->getUser()->getId(), PDO::PARAM_INT)->queryAll();
        foreach ($rawData as $data) {
            $loginLogs[$formatter->asDate($data['login_at'])][] = $data;
        }

        return $this->render('loginLogs', [
                'loginLogs' => $loginLogs
        ]);
    }

    /**
     * 设置当前用户管理的租赁
     * @param integer $tenantId
     * @return mixed
     */
    public function actionChangeTenant($tenantId)
    {
        MTS::setTenantData($tenantId);

        return $this->redirect(['default/index']);
    }

    /**
     * 选择租赁站点
     * @return mixed
     */
    public function actionChoiceTenant()
    {
        $this->layout = 'base';
        $tenants = Yii::$app->getDb()->createCommand('SELECT [[id]], [[name]], [[domain_name]], [[description]] FROM {{%tenant}} WHERE [[enabled]] = :enabled AND [[id]] IN (SELECT [[tenant_id]] FROM {{%tenant_user}} WHERE [[user_id]] = :userId)')->bindValues([
                ':enabled' => Option::BOOLEAN_TRUE,
                ':userId' => Yii::$app->getUser()->getId()
            ])->queryAll();

        return $this->render('choiceTenant', [
                'tenants' => $tenants,
        ]);
    }

    public function findCurrentUserModel()
    {
        if (($model = User::findOne(Yii::$app->getUser()->getId())) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
