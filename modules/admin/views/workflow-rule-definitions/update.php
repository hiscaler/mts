<?php
/* @var $this yii\web\View */
/* @var $model app\models\WorkflowRuleDefinition */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
            'modelClass' => Yii::t('model', 'Workflow Rule Definition'),
        ]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Workflow Rules'), 'url' => ['workflow-rules/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Workflow Rule Definitions'), 'url' => ['index', 'ruleId' => $model->rule_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index', 'ruleId' => $model->rule_id]],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create', 'ruleId' => $model->rule_id]],
];
?>
<div class="workflow-rule-definition-update">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
