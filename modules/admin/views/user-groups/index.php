<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TenantUserGroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'User Groups');
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Search'), 'url' => '#'],
];
?>
<div class="tenant-user-group-index">

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['class' => 'serial-number']
            ],
            [
                'attribute' => 'alias',
                'contentOptions' => ['class' => 'user-group-alias'],
            ],
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a($model['name'], ['update', 'id' => $model['id']]);
                }
                ],
                [
                    'attribute' => 'enabled',
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
                    'template' => '{update} {delete}',
                    'headerOptions' => ['class' => 'last'],
                    'contentOptions' => ['class' => 'btn-2'],
                ],
            ],
        ]);
        ?>

</div>
