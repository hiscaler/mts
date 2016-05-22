<?php
/* @var $this yii\web\View */
/* @var $model app\models\WorkflowRule */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
            'modelClass' => Yii::t('model', 'Workflow Rule'),
        ]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Workflow Rules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'View'), 'url' => ['view', 'id' => $model->id]],
];
?>
<div class="workflow-rule-update">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
