<?php

namespace app\modules\admin\widgets;

use app\models\Tenant;
use app\models\MTS;
use yadjet\helpers\DatetimeHelper;
use Yii;
use yii\base\Widget;
use yii\helpers\Inflector;
use yii\helpers\Json;

/**
 * 统计
 */
class Statistics extends Widget
{

    public function getData()
    {
        $items = [];
        $now = time();
        $currentHour = date('h', $now);
        $today = DatetimeHelper::getTodayRange($now);
        $hour = 0;
        $db = Yii::$app->getDb();
        $tablePrefix = $db->tablePrefix;
        $tables = [];
        $rejectStatisticsTables = isset(Yii::$app->params['rejectStatisticsTables']) ? Yii::$app->params['rejectStatisticsTables'] : [];
        foreach (Tenant::modules() as $module) {
            $tableName = str_replace($tablePrefix, '', MTS::modelName2TableName($module));
            if (!in_array($tableName, $rejectStatisticsTables)) {
                $tables[] = $tableName;
            }
        }
        $tenantId = MTS::getTenantId();
        foreach ($tables as $table) {
            for ($i = $today[0]; $i <= $today[1]; $i += 3600) {
                $items[$table]['name'] = Yii::t('model', Inflector::camel2words(Inflector::id2camel($table, '_')));
                if ($i > $now) {
                    $items[$table]['data'][] = 0;
                } else {
                    $cmd = $db->createCommand('SELECT COUNT(*) FROM {{%' . $table . '}} WHERE [[tenant_id]] = :tenantId AND [[created_at]] BETWEEN :begin AND :end')->bindValues([
                        ':tenantId' => $tenantId,
                        ':begin' => $i,
                        ':end' => $i + 3600
                    ]);
                    if ($hour != ($currentHour - 1)) {
                        $cmd = $cmd->cache(3600 * (23 - $hour));
                    }
                    $items[$table]['data'][] = (int) $cmd->queryScalar();
                }
                $hour++;
            }
        }

        return array_values($items);
    }

    public function run()
    {
        return $this->render('Statistics', [
                'tenantName' => MTS::getTenantName(),
                'data' => Json::encode($this->getData()),
        ]);
    }

}
