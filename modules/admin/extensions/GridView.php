<?php

namespace app\modules\admin\extensions;

use app\models\MTS;
use Yii;
use yii\grid\DataColumn;

/**
 * 改进后的 GridView，支持根据设定显示所需列
 * @author hiscaler<hiscaler@gmail.com>
 */
class GridView extends \yii\grid\GridView
{

    protected function initColumns()
    {
        if (empty($this->columns)) {
            $this->guessColumns();
        }
        $invisibleColumns = $this->invisibleColumns();
        foreach ($this->columns as $i => $column) {
            if (is_string($column)) {
                $column = $this->createDataColumn($column);
            } else {
                $column = Yii::createObject(array_merge([
                        'class' => $this->dataColumnClass ? : DataColumn::className(),
                        'grid' => $this
                            ], $column));
            }

            $attribute = false;
            if ($column->hasProperty('attribute')) {
                $attribute = $column->attribute;
            }
            if (!$column->visible || ($attribute && in_array($attribute, $invisibleColumns))) {
                unset($this->columns[$i]);
                continue;
            }
            $this->columns[$i] = $column;
        }
    }

    /**
     * 设置为不可见的列
     * @return array
     */
    private function invisibleColumns()
    {
        $columns = [];
        $configs = $this->getColumnConfigs();
        foreach ($configs as $config) {
            if (!$config['visible']) {
                $columns[] = $config['attribute'];
            }
        }

        return $columns;
    }

    private function getColumnConfigs()
    {
        $name = str_replace('grid-view', '', $this->id);
        $configs = Yii::$app->getDb()->createCommand('SELECT [[name]], [[attribute]], [[css_class]], [[css_style]], [[visible]] FROM {{%grid_column_config}} WHERE [[name]] = :name AND [[user_id]] = :userId AND [[tenant_id]] = :tenantId')->bindValues([
                ':name' => 'app-models-' . \yii\helpers\Inflector::id2camel($name),
                ':userId' => Yii::$app->getUser()->getId(),
                ':tenantId' => MTS::getTenantId()
            ])->queryAll();

        return $configs;
    }

}
