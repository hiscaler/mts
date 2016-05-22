<?php
/* @var $this yii\web\View */
/* @var $model app\models\WorkflowRuleDefinition */

$this->title = Yii::t('app', 'Create {modelClass}', [
            'modelClass' => Yii::t('model', 'Workflow Rule Definition'),
        ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Workflow Rules'), 'url' => ['workflow-rules/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Workflow Rule Definitions'), 'url' => ['index', 'ruleId' => $rule->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index', 'ruleId' => $model->rule_id]],
];
?>
<div class="workflow-rule-definition-create">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
