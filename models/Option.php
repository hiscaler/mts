<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

class Option
{

    /**
     * Boolean options
     * @return array
     */
    public static function booleanOptions()
    {
        return [
            Constant::BOOLEAN_FALSE => Yii::t('app', 'No'),
            Constant::BOOLEAN_TRUE => Yii::t('app', 'Yes'),
        ];
    }

    /**
     * 排序下拉列表框数据
     * @param integer $start
     * @param integer $max
     * @return array
     */
    public static function orderingOptions($start = 0, $max = 60)
    {
        $options = [];
        for ($i = $start; $i <= $max; $i++) {
            $options[$i] = $i;
        }
        return $options;
    }

    /**
     * Data status values
     * @return array
     */
    public static function statusOptions()
    {
        return [
            Constant::STATUS_DRAFT => Yii::t('app', 'Draft'),
            Constant::STATUS_PENDING => Yii::t('app', 'Pending'),
            Constant::STATUS_PUBLISHED => Yii::t('app', 'Published'),
            Constant::STATUS_DELETED => Yii::t('app', 'Deleted'),
            Constant::STATUS_ARCHIVED => Yii::t('app', 'Archived')
        ];
    }

    public static function modelNameOptions()
    {
        $options = [];
        $tenantModules = Tenant::modules();
        $contentModels = ArrayHelper::getValue(Yii::$app->params, 'contentModules', []);
        foreach ($contentModels as $modelName => $item) {
            if (in_array($modelName, $tenantModules)) {
                $options[$modelName] = Yii::t('app', $item['label']);
            }
        }
        return $options;
    }

    /**
     * 模块列表
     * @param boolean $all // 是否返回所有模块
     * @return array
     */
    public static function modulesOptions($all = false)
    {
        $options = [];
        if ($all === true) {
            $tenantModules = Tenant::modules();
        }
        $contentModels = ArrayHelper::getValue(Yii::$app->params, 'contentModules', []);
        foreach ($contentModels as $modelName => $item) {
            if ($all === false && in_array($modelName, $tenantModules)) {
                continue;
            }
            $options[$modelName] = Yii::t('app', $item['label']);
        }

        return $options;
    }

}
