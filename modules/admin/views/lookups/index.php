<?php

use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LookupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Lookups');
$this->params['breadcrumbs'][] = $this->title;
$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Grid Column Config'), 'url' => ['grid-column-configs/index', 'name' => 'app-models-Lookup'], 'htmlOptions' => ['class' => 'grid-column-config', 'data-reload-object' => 'grid-view-lookups']],
    ['label' => Yii::t('app', 'Search'), 'url' => '#'],
];
?>
    <div class="lookup-index">
        <?= $this->render('_search', ['model' => $searchModel]); ?>
        <?php
        Pjax::begin([
            'formSelector' => '#form-lookups',
            'linkSelector' => '#grid-view-lookups a',
        ]);
        echo \app\modules\admin\components\GridView::widget([
            'id' => 'grid-view-lookups',
            'name' => 'app-models-Lookup',
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'contentOptions' => ['class' => 'serial-number']
                ],
                [
                    'attribute' => 'label',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return \yii\helpers\Html::a($model['label'], ['update', 'id' => $model['id']]);
                    },
                    'contentOptions' => ['class' => 'lookup-label'],
                ],
                'description',
                [
                    'attribute' => 'value',
                    'format' => 'raw',
                    'value' => function ($model) {
                        $value = unserialize($model['value']);
                        if (is_string($value) && $value) {
                            return StringHelper::truncate($value, 20);
                        } elseif (is_array($value)) {
                            return var_export($value, true);
                        } else {
                            return $value;
                        }
                    }
                ],
                [
                    'attribute' => 'return_type_text',
                    'contentOptions' => ['class' => 'lookup-return-type center'],
                ],
                [
                    'attribute' => 'enabled',
                    'format' => 'boolean',
                    'contentOptions' => ['class' => 'boolean pointer lookup-enabled-handler'],
                ],
                [
                    'attribute' => 'created_by',
                    'value' => function ($model) {
                        return $model['creater']['nickname'];
                    },
                    'contentOptions' => ['class' => 'username']
                ],
                [
                    'attribute' => 'created_at',
                    'format' => 'date',
                    'contentOptions' => ['class' => 'date']
                ],
                [
                    'attribute' => 'updated_by',
                    'value' => function ($model) {
                        return $model['updater']['nickname'];
                    },
                    'contentOptions' => ['class' => 'username']
                ],
                [
                    'attribute' => 'updated_at',
                    'format' => 'date',
                    'contentOptions' => ['class' => 'date']
                ],
                /* [
                  'attribute' => 'deleted_by',
                  'value' => function ($model) {
                  return $model['deleter']['nickname'];
                  },
                  'contentOptions' => ['class' => 'username']
                  ],
                  [
                  'attribute' => 'deleted_at',
                  'format' => 'date',
                  'contentOptions' => ['class' => 'date']
                  ], */
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete}',
                    'headerOptions' => ['class' => 'buttons-2 last'],
                ],
            ],
        ]);
        Pjax::end();
        ?>
    </div>
<?php
$this->registerJs('yadjet.actions.toggle("table td.lookup-enabled-handler img", "' . Url::toRoute('toggle') . '");');
    