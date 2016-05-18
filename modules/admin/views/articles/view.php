<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Article */

$this->title = $model->title;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Articles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model['title'];

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Update'), 'url' => ['update', 'id' => $model['id']]],
];
?>
<div class="article-view">

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ordering',
            'alias',
            'title',
            'tags',
            'keywords',
            'description:raw',
            'content:raw',
            'picture_path:image',
            'status:dataStatus',
            'enabled:boolean',
            [
                'attribute' => 'created_by',
                'value' => $model['creater']['nickname']
            ],
            'created_at:datetime',
            [
                'attribute' => 'updated_by',
                'value' => $model['updater']['nickname']
            ],
            'updated_at:datetime',
            [
                'attribute' => 'deleted_by',
                'value' => $model['deleter']['nickname']
            ],
            'deleted_at:datetime',
        ],
    ])
    ?>

</div>
