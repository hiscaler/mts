<?php

namespace app\modules\admin\widgets;

use Yii;
use yii\base\Widget;

/**
 * 用户登录日志
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class UserLoginLogs extends Widget
{

    /**
     * 为 true 只显示当前用户的登录日志，false 则显示全部登录日志
     * @var boolean
     */
    public $viewOwner = true;

    public function getItems()
    {
        $items = [];
        $formatter = Yii::$app->getFormatter();
        $sql = 'SELECT [[t.login_ip]], [[t.client_informations]], [[t.login_at]] FROM {{%user_login_log}} t WHERE [[t.user_id]] = :userId';
        $query = (new \yii\db\Query())->select(['t.login_ip', 't.client_informations', 't.login_at'])
            ->from('{{%user_login_log}} t');


        if (!$this->viewOwner) {
            $query->leftJoin('{{%user}} u', '[[t.user_id]] = [[u.id]]')
                ->addSelect('u.username');
        } else {
            $query->where(['t.user_id' => Yii::$app->getUser()->getId()]);
        }
        $query->orderBy(['t.login_at' => SORT_DESC]);
        $rawData = $query->all();
        foreach ($rawData as $data) {
            $items[$formatter->asDate($data['login_at'])][] = $data;
        }

        return $items;
    }

    public function run()
    {
        return $this->render('UserLoginLogs', [
                'items' => $this->getItems(),
                'viewOwner' => $this->viewOwner,
        ]);
    }

}
