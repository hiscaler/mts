<?php
/* @var $this yii\web\View */
/* @var $model app\models\WorkflowRule */

$this->title = Yii::t('app', 'Create {modelClass}', [
            'modelClass' => Yii::t('model', 'Workflow Rule'),
        ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Workflow Rules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
];
?>
<div class="workflow-rule-create">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
