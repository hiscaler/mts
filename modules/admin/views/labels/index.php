<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LabelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Labels');
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Search'), 'url' => '#'],
];
?>
<div class="attribute-index">

    <?= $this->render('_search', ['model' => $searchModel]) ?>

    <?php
    Pjax::begin([
        'formSelector' => '#form-attribute-search',
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
                'contentOptions' => ['class' => 'number'],
            ],
            [
                'attribute' => 'alias',
                'contentOptions' => ['style' => 'width: 60px'],
            ],
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($model) {
                    return "<span class=\"pk\">[ {$model['id']} ]</span>" . Html::a($model['name'], ['update', 'id' => $model['id']]);
                }
                ],
                [
                    'attribute' => 'frequency',
                    'contentOptions' => ['class' => 'number'],
                ],
                [
                    'attribute' => 'status',
                    'format' => 'boolean',
                    'contentOptions' => ['class' => 'boolean pointer enabled-handler'],
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
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete}',
                    'headerOptions' => ['class' => 'btns-2 last'],
                ],
            ],
        ]);
        Pjax::end();
        ?>

    </div>

    <?php
    $this->registerJs('yadjet.actions.toggle("table td.enabled-handler img", "' . Url::toRoute('toggle') . '");');
    $this->registerJs('yadjet.actions.toggle("table td.entity-enabled-handler img", "' . Url::toRoute('toggle-entity-enabled') . '");');
    