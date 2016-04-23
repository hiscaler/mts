<?php

use app\modules\admin\widgets\Statistics;
use app\modules\admin\widgets\UserLoginLogs;
use app\models\MTS;

/* @var $this yii\web\View */

if (MTS::getTenantId()) {
    echo Statistics::widget();
}

$dependency = [
    'class' => 'yii\caching\DbDependency',
    'sql' => 'SELECT [[last_login_datetime]] FROM {{%user}} WHERE [[id]] = :id',
    'params' => [':id' => Yii::$app->getUser()->getId()]
];
if ($this->beginCache(UserLoginLogs::className(), ['dependency' => $dependency])) {
    echo UserLoginLogs::widget();
    $this->endCache();
}
