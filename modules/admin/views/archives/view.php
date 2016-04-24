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
            'node_id',
            'model_name',
            'title',
            'keyword',
            'description:ntext',
            'tags',
            'has_thumbnail',
            'thumbnail',
            'author',
            'source',
            'status',
            'enabled',
            'published_datetime:datetime',
            'clicks_count',
            'enabled_comment',
            'comments_count',
            'ordering',
            'tenant_id',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'deleted_at',
            'deleted_by',
        ],
    ])
    ?>

</div>
