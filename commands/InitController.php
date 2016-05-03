<?php

namespace app\commands;

use app\models\User;
use PDO;
use Yii;
use yii\base\Security;

/**
 * 初始化数据
 */
class InitController extends \yii\console\Controller
{

    /**
     * 初始化默认管理用户
     * @return int
     */
    private function _initAdminUser()
    {
        $username = 'admin';
        $db = Yii::$app->getDb();
        $command = $db->createCommand('SELECT COUNT(*) FROM {{%user}} WHERE username = :username');
        $command->bindValue(':username', $username, PDO::PARAM_STR);
        $exist = $command->queryScalar();
        if (!$exist) {
            $now = time();
            $security = new Security;
            $columns = [
                'username' => $username,
                'nickname' => 'admin',
                'auth_key' => $security->generateRandomString(),
                'password_hash' => $security->generatePasswordHash('admin'),
                'password_reset_token' => null,
                'email' => 'admin@example.com',
                'register_ip' => ip2long('::1'),
                'login_count' => 0,
                'last_login_ip' => null,
                'last_login_datetime' => null,
                'status' => User::STATUS_ACTIVE,
                'created_by' => 0,
                'created_at' => $now,
                'updated_by' => 0,
                'updated_at' => $now,
            ];
            $db->createCommand()->insert('{{%user}}', $columns)->execute();
        } else {
            echo "'{$username}' is exists.\r\n";
        }

        return 0;
    }

    private function _initLookup($tenantId = 1)
    {
        echo "Begin init lookup values\r\n";
        $map = [
            'site.name' => [
                'description' => '站点名称',
                'value' => '站点名称',
                'return_type' => \app\models\Lookup::RETURN_TYPE_STRING,
            ],
            'icp' => [
                'description' => 'ICP 备案号',
                'value' => '201601010001',
                'return_type' => \app\models\Lookup::RETURN_TYPE_STRING,
            ],
            'statistic.code' => [
                'description' => '统计代码',
                'value' => '',
                'return_type' => \app\models\Lookup::RETURN_TYPE_STRING,
            ],
        ];
        $now = time();
        $rows = [];
        foreach ($map as $key => $data) {
            $rows[] = [
                'label' => $key,
                'description' => $data['description'],
                'value' => $data['value'],
                'return_type' => $data['return_type'],
                'enabled' => 1,
                'tenant_id' => (int) $tenantId,
                'created_by' => 1,
                'created_at' => $now,
                'updated_by' => 1,
                'updated_at' => $now,
                'deleted_by' => null,
                'deleted_at' => null,
            ];
        }

        Yii::$app->getDb()->createCommand()->batchInsert('{{%lookup}}', ['label', 'description', 'value', 'return_type', 'enabled', 'tenant_id', 'created_by', 'created_at', 'updated_by', 'updated_at', 'deleted_by', 'deleted_at'], $rows)->execute();
    }

    public function actionIndex()
    {
        echo "Begin ......\r\n";
        $this->_initAdminUser();
        $this->_initLookup();
        echo 'Done.';
        return 0;
    }

}
