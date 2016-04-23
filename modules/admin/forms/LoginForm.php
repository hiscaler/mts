<?php

namespace app\modules\admin\forms;

use app\models\User;
use app\models\UserLoginLog;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{

    public $username;
    public $password;
    public $rememberMe = true;
    public $verifyCode;
    private $_user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['username', 'validateUser'],
            ['password', 'validatePassword'],
            ['verifyCode', 'captcha', 'captchaAction' => 'default/captcha'],
        ];
    }

    /**
     * 验证用户是否有效以及该用户是否及时修改密码
     * @param string $attribute
     * @param array $params
     */
    public function validateUser($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user) {
                $this->addError($attribute, Yii::t('app', 'Incorrect username.'));
            } else {
                $lastChangePasswordTime = $user->last_change_password_time;
                if (!$lastChangePasswordTime) {
                    $lastChangePasswordTime = $user->created_at;
                }
                $intervalDays = isset(Yii::$app->params['security']['changePasswordIntervalDays']) ? Yii::$app->params['security']['changePasswordIntervalDays'] : 30; // 默认 30 天修改一次
                $intervalDays = (int) $intervalDays ? : 30;
                if (time() - $lastChangePasswordTime >= 86400 * $intervalDays) {
                    $this->addError($attribute, Yii::t('app', "{username} deny login, because this user didn't change the password in time.", ['username' => $this->username]));
                }
            }
        }
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('app', 'Incorrect username or password.'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            $logined = Yii::$app->getUser()->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
            if ($logined) {
                // Add login information
                Yii::$app->getDb()->createCommand('UPDATE {{%user}} SET [[login_count]] = [[login_count]] + 1, [[last_login_ip]] = :loginIp, [[last_login_time]] = :loginTime WHERE [[id]] = :id')->bindValues([
                    ':loginIp' => Yii::$app->getRequest()->userIP,
                    ':loginTime' => time(),
                    ':id' => Yii::$app->getUser()->getId()
                ])->execute();
                // Write user login log
                UserLoginLog::write();
            }

            return $logined;
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'verifyCode' => '验证码',
            'rememberMe' => '记住登录',
        ];
    }

}
