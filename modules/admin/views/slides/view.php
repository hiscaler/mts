<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Slide */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Slides'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Update'), 'url' => ['update', 'id' => $model->id]],
];
?>
<div class="slide-view">

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ordering',
            [
                'attribute' => 'group_id',
                'value' => Yii::$app->getFormatter()->asGroupName('slide.group', $model['group_id']),
                'format' => 'raw',
            ],
            'title',
            [
                'attribute' => 'url',
                'value' => \yii\helpers\Html::a($model['url'], $model['url'], ['target' => '_blank']),
                'format' => 'raw',
            ],
            'url_open_target:slideUrlOpenTarget',
            'picture:image',
            'enabled:boolean',
            'status:dataStatus',
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
