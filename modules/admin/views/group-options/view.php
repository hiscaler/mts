<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\GroupOption */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Group Options'), 'url' => ['index']];
$this->params['breadcrumbs'][] = "$model->group_name  ({$model->text})";

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Update'), 'url' => ['update', 'id' => $model->id]],
];
?>
<div class="group-option-view">

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'group_name',
            'text',
            'value',
            'alias',
            'enabled:boolean',
            'defaulted:boolean',
            'ordering',
            'description',
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
        ],
    ])
    ?>

</div>
