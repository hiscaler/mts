<?php

namespace app\modules\admin\controllers;

use app\models\MTS;
use app\models\User;
use Yii;

/**
 * Controller base class
 */
class Controller extends \yii\web\Controller
{

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            // 如果不是管理员并且没有选择管理站点的一律跳到站点选择界面
            if (MTS::getUserRole() !== User::ROLE_ADMINISTRATOR && !MTS::getTenantId() && ($this->id != 'default' && !in_array($action->id, ['choice-tenant', 'change-tenant', 'login', 'logout', 'error']))) {
                $this->redirect(['default/choice-tenant']);
            }

            $formatter = Yii::$app->getFormatter();
            $language = MTS::getLanguage();
            if ($language) {
                Yii::$app->language = $language;
            }
            $timezone = MTS::getTimezone();
            if ($timezone) {
                Yii::$app->timeZone = $timezone;
            }

            $formatter->defaultTimeZone = Yii::$app->timeZone;
            $dateFormat = MTS::getTenantValue('dateFormat', 'php:Y-m-d');
            if ($dateFormat) {
                $formatter->dateFormat = $dateFormat;
            }
            $timeFormat = MTS::getTenantValue('timeFormat', 'php:H:i:s');
            if ($timeFormat) {
                $formatter->timeFormat = $timeFormat;
            }
            $datetimeFormat = MTS::getTenantValue('datetimeFormat', 'php:Y-m-d H:i:s');
            if ($datetimeFormat) {
                $formatter->datetimeFormat = $datetimeFormat;
            }

            return true;
        }

        return false;
    }

}
