<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Archive */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Archives'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index', 'modelName' => $model->model_name]],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create', 'modelName' => $model->model_name]],
    ['label' => Yii::t('app', 'Update'), 'url' => ['update', 'id' => $model->id]],
];
?>
<div class="archive-view">

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'ordering',
            'node.name',
            'model_name',
            'title',
            'keywords',
            'description:ntext',
            'tags',
            'thumbnail:image',
            'author',
            'source',
            'status:dataStatus',
            'enabled:boolean',
            'published_datetime:datetime',
            'clicks_count',
            'enabled_comment:boolean',
            'comments_count',
            'created_at:datetime',
            [
                'attribute' => 'created_by',
                'value' => $model['creater']['username']
            ],
            'updated_at:datetime',
            [
                'attribute' => 'updated_by',
                'value' => $model['updater']['username']
            ],
            'deleted_at:datetime',
            [
                'attribute' => 'deleted_by',
                'value' => $model['deleter']['username']
            ],
        ],
    ])
    ?>

</div>
