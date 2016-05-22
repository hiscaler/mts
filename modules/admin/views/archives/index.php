<?php

use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ArchiveSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Archives');
$this->params['breadcrumbs'][] = $this->title;

$gridViewId = 'grid-view-' . lcfirst(str_replace('app-models-', '', $modelName));
$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index', 'modelName' => $modelName]],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create', 'modelName' => $modelName]],
    ['label' => Yii::t('app', 'Grid Column Config'), 'url' => ['grid-column-configs/index', 'name' => $modelName], 'htmlOptions' => ['class' => 'grid-column-config', 'data-reload-object' => $gridViewId]],
    ['label' => Yii::t('app', 'Search'), 'url' => '#'],
];
?>
<div class="archive-index">

    <?= $this->render('_search', ['model' => $searchModel]); ?>
    
    <?php Pjax::begin(); ?>
    
    <?=
    \app\modules\admin\extensions\GridView::widget([
        'id' => $gridViewId,
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
                'attribute' => 'node.name',
                'contentOptions' => ['class' => 'node-name'],
            ],
            'model_name',
            [
                'attribute' => 'title',
                'format' => 'raw',
                'value' => function ($model) {
                    $output = "<span class=\"pk\">[ {$model['id']} ]</span>" . Html::a($model['title'], ['update', 'id' => $model['id']], ['class' => $model['has_thumbnail'] ? 'thumbnail' : '']);
                    $words = [];
//                    foreach ($model['customeLables'] as $label) {
//                        $words[] = $label['name'];
//                    }
                    $sentence = Inflector::sentence($words, '、', null, '、');
                    if (!empty($sentence)) {
                        $sentence = "<span class=\"attributes\">{$sentence}</span>";
                    }

                    return $sentence . $output;
                },
            ],
            'keywords',
            // 'has_thumbnail',
            // 'author',
            // 'source',
            [
                'attribute' => 'status',
                'format' => 'dataStatus',
                'contentOptions' => ['class' => 'data-status'],
            ],
            [
                'attribute' => 'enabled',
                'format' => 'boolean',
                'contentOptions' => ['class' => 'boolean'],
            ],
            'published_datetime:datetime',
            [
                'attribute' => 'clicks_count',
                'contentOptions' => ['class' => 'number'],
            ],
            [
                'attribute' => 'enabled_comment',
                'format' => 'boolean',
                'contentOptions' => ['class' => 'boolean'],
            ],
            [
                'attribute' => 'comments_count',
                'contentOptions' => ['class' => 'number'],
            ],
            [
                'attribute' => 'created_at',
                'format' => 'date',
                'contentOptions' => ['class' => 'date'],
            ],
            'created_by',
            [
                'attribute' => 'updated_at',
                'format' => 'date',
                'contentOptions' => ['class' => 'date'],
            ],
            'updated_by',
            [
                'attribute' => 'deleted_at',
                'format' => 'date',
                'contentOptions' => ['class' => 'date'],
            ],
            'deleted_by',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'headerOptions' => ['class' => 'last'],
                'contentOptions' => ['class' => 'buttons-3']
            ],
        ],
    ]);
    ?>
    <?php Pjax::end(); ?>
</div>
