<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Nodes');
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Search'), 'url' => '#'],
];
?>
<div class="node-index">

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    Pjax::begin([
        'formSelector' => '#form-nodes-search',
    ]);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions' => function($model, $key, $index, $grid) {
            return [
                'class' => $model['enabled'] ? 'enabled' : 'disabled',
            ];
        },
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['class' => 'serial-number']
            ],
            [
                'attribute' => 'ordering',
                'contentOptions' => ['class' => 'ordering'],
                'label' => Yii::t('app', 'Ordering'),
            ],
            [
                'attribute' => 'name',
                'value' => function($model) {
                    return '<span class="level level-' . $model['level'] . '">&nbsp;</span>' . Html::a($model['name'], ['update', 'id' => $model['id']]) . (!empty($model['alias']) ? " ( {$model['alias']} )" : '') . " [ {$model['id']} ] <span class=\"node-parameters\" data-value=\"" .Inflector::sentence(explode("\r\n", $model['parameters']), "</br>", "</br>", "</br>"). '">&nbsp;</span>';
                },
                'format' => 'raw',
                'contentOptions' => ['class' => 'node-tree'],
                'label' => Yii::t('node', 'Name'),
            ],
            [
                'attribute' => 'model_name',
                'format' => 'modelName',
                'label' => Yii::t('app', 'Model Name'),
                'contentOptions' => ['class' => 'model-name center']
            ],
            [
                'attribute' => 'direct_data_count',
                'contentOptions' => ['class' => 'number'],
                'label' => Yii::t('node', 'Direct Data Count'),
            ],
            [
                'attribute' => 'relation_data_count',
                'contentOptions' => ['class' => 'number'],
                'label' => Yii::t('node', 'Relation Data Count'),
            ],
            [
                'attribute' => 'enabled',
                'format' => 'boolean',
                'contentOptions' => ['class' => 'boolean enabled-handler pointer'],
                'label' => Yii::t('app', 'Enabled'),
            ],
            [
                'attribute' => 'entity_status',
                'format' => 'dataStatus',
                'contentOptions' => ['class' => 'data-status'],
                'label' => Yii::t('node', 'Entity Status'),
            ],
            [
                'attribute' => 'entity_enabled',
                'format' => 'boolean',
                'contentOptions' => ['class' => 'boolean entity-enabled-handler pointer'],
                'label' => Yii::t('node', 'Entity Enabled'),
            ],
            [
                'attribute' => 'created_by',
                'value' => function($model) {
                    return $model['creater_nickname'];
                },
                'contentOptions' => ['class' => 'username'],
                'label' => Yii::t('app', 'Created By')
            ],
            [
                'attribute' => 'created_at',
                'format' => 'date',
                'contentOptions' => ['class' => 'date'],
                'label' => Yii::t('app', 'Created At'),
            ],
            [
                'attribute' => 'updated_by',
                'value' => function($model) {
                    return $model['updater_nickname'];
                },
                'contentOptions' => ['class' => 'username'],
                'label' => Yii::t('app', 'Updated By')
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'date',
                'contentOptions' => ['class' => 'date'],
                'label' => Yii::t('app', 'Updated At'),
            ],
            [
                'attribute' => 'deleted_by',
                'value' => function($model) {
                    return $model['deleter_nickname'];
                },
                'contentOptions' => ['class' => 'username'],
                'label' => Yii::t('app', 'Deleted By')
            ],
            [
                'attribute' => 'deleted_at',
                'format' => 'date',
                'contentOptions' => ['class' => 'date'],
                'label' => Yii::t('app', 'Deleted At'),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'headerOptions' => ['class' => 'last'],
                'contentOptions' => ['class' => 'btn-2']
            ],
        ],
    ]);
    Pjax::end();
    ?>

</div>

<?php
$js = 'yadjet.actions.toggle("table td.enabled-handler img", "' . Url::toRoute('toggle') . '");';
$js .= 'yadjet.actions.toggle("table td.entity-enabled-handler img", "' . Url::toRoute('toggle-entity-enabled') . '");';
$js .= <<<EOT
jQuery(document).on('mouseover', 'span.node-parameters', function () {
    layer.tips($(this).attr('data-value'), this, {
        style: ['background-color:#78BA32; color:#fff', '#78BA32'],
        closeBtn: [0, true],
        guide: 1
    });
});
EOT;

$this->registerJs($js);
