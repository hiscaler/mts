<?php

use app\models\Constant;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FriendlyLinkSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$formatter = Yii::$app->getFormatter();

$this->title = Yii::t('app', 'Friendly Links');
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Search'), 'url' => '#'],
];
?>
<div class="friendly-link-index">

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    Pjax::begin([
        'formSelector' => '#form-friendly-links-search',
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
                    return $formatter->asGroupName('friendly.link.group', $model['group_id']);
                },
                'format' => 'raw',
                'contentOptions' => ['class' => 'group-name'],
            ],
            [
                'attribute' => 'type',
                'format' => 'friendlyLinkType',
                'contentOptions' => ['class' => 'friendly-link-type center'],
            ],
            [
                'attribute' => 'title',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a($model['title'], ['update', 'id' => $model['id']]);
                }
            ],
            'description',
            [
                'attribute' => 'url',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a($model['url'], $model['url'], ['target' => '_blank']);
                }
            ],
            [
                'attribute' => 'url_open_target',
                'format' => 'friendlyLinkUrlOpenTarget',
                'contentOptions' => ['class' => 'friendly-link-url-open-target center'],
            ],
            [
                'attribute' => 'status',
                'format' => 'dataStatus',
                'contentOptions' => ['class' => 'data-status'],
            ],
            [
                'attribute' => 'enabled',
                'format' => 'boolean',
                'contentOptions' => ['class' => 'boolean pointer boolean-handler'],
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
                'contentOptions' => ['class' => 'username']
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'date',
                'contentOptions' => ['class' => 'date']
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
                'template' => '{update} {delete} {undo}',
                'buttons' => [
                    'delete' => function ($url, $model, $key) {
                        return $model['status'] != Constant::STATUS_DELETED ? Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('yii', 'Delete'),
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]) : '';
                    },
                    'undo' => function ($url, $model, $key) {
                        return $model['status'] == Constant::STATUS_DELETED ? Html::a('<span class="glyphicon glyphicon-undo"></span>', $url, [
                            'title' => Yii::t('app', 'Undo'),
                            'data-confirm' => Yii::t('app', 'Are you sure you want to undo this item?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]) : '';
                    }
                ],
                'headerOptions' => ['class' => 'last'],
                'contentOptions' => ['class' => 'btn-2'],
            ],
        ],
    ]);
    Pjax::end();
    ?>

</div>

<?php
$this->registerJs('yadjet.actions.toggle("table td.boolean-handler img", "' . Url::toRoute('toggle') . '");');
