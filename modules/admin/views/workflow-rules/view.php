<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\WorkflowRule */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Workflow Rules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Update'), 'url' => ['update', 'id' => $model->id]],
];
?>
<div class="workflow-rule-view">

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'description:ntext',
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
