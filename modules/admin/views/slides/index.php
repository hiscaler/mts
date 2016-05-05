<?php

use app\models\Option;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SlideSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$formatter = Yii::$app->getFormatter();

$this->title = Yii::t('app', 'Slides');
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Search'), 'url' => '#'],
];
?>
<div class="slide-index">

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    Pjax::begin([
        'formSelector' => '#form-search-slides',
    ]);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['class' => 'serial-number']
            ],
            [
                'attribute' => 'ordering',
                'contentOptions' => ['class' => 'ordering'],
            ],
            [
                'attribute' => 'group_id',
                'value' => function($model, $key, $index, $grid) use ($formatter) {
                    return $formatter->asGroupName('slide.group', $model['group_id']);
                },
                'format' => 'raw',
                'contentOptions' => ['class' => 'group-name'],
            ],
            [
                'attribute' => 'title',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a($model['title'], ['update', 'id' => $model['id']]);
                }
            ],
            [
                'attribute' => 'url',
                'value' => function($model) {
                    return Html::a($model['url'], $model['url'], ['target' => '_blank']);
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'url_open_target',
                'format' => 'slideUrlOpenTarget',
                'contentOptions' => ['class' => 'center'],
            ],
            [
                'attribute' => 'enabled',
                'format' => 'boolean',
                'contentOptions' => ['class' => 'boolean pointer enabled-handler'],
            ],
            [
                'attribute' => 'status',
                'format' => 'dataStatus',
                'contentOptions' => ['class' => 'data-status'],
            ],
            [
                'attribute' => 'created_by',
                'value' => function($model) {
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
                'value' => function($model) {
                    return $model['updater']['nickname'];
                },
                'contentOptions' => ['class' => 'username rb-updated-by']
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'date',
                'contentOptions' => ['class' => 'date rb-updated-at']
            ],
            [
                'attribute' => 'deleted_by',
                'value' => function($model) {
                    return $model['deleter']['nickname'];
                },
                'contentOptions' => ['class' => 'username']
            ],
            [
                'attribute' => 'deleted_at',
                'format' => 'date',
                'contentOptions' => ['class' => 'date']
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {undo}',
                'buttons' => [
                    'delete' => function ($url, $model, $key) {
                        return $model['status'] != \app\models\Constant::STATUS_DELETED ? Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('yii', 'Delete'),
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]) : '';
                    },
                    'undo' => function ($url, $model, $key) {
                        return $model['status'] == \app\models\Constant::STATUS_DELETED ? Html::a('<span class="glyphicon glyphicon-undo"></span>', $url, [
                            'title' => Yii::t('app', 'Undo'),
                            'data-confirm' => Yii::t('app', 'Are you sure you want to undo this item?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]) : '';
                    }
                ],
                'headerOptions' => ['class' => 'last'],
                'contentOptions' => ['class' => 'btn-3'],
            ],
        ],
    ]);
    Pjax::end();
    ?>

</div>

<?php
$this->registerJs('yadjet.actions.toggle("table td.enabled-handler img", "' . Url::toRoute('toggle') . '");');
