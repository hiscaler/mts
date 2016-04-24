<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ArchiveSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Archives');
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index', 'modelName' => $modelName]],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create', 'modelName' => $modelName]],
    ['label' => Yii::t('app', 'Search'), 'url' => '#'],
];
?>
<div class="archive-index">

    <?php Pjax::begin(); ?>  
    <?=
    GridView::widget([
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
            'node_id',
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
            'keyword',
            // 'has_thumbnail',
            // 'author',
            // 'source',
            'status',
            'enabled',
            'published_datetime:datetime',
            'clicks_count',
            'enabled_comment',
            'comments_count',
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
                'template' => '{update} {delete}',
                'headerOptions' => ['class' => 'last'],
                'contentOptions' => ['class' => 'btn-2']
            ],
        ],
    ]);
    ?>
    <?php Pjax::end(); ?>
</div>
