<?php

use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\WorkflowRuleDefinitionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Workflow Rule Definitions');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Workflow Rules'), 'url' => ['workflow-rules/index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index', 'ruleId' => $rule->id]],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create', 'ruleId' => $rule->id]],
    ['label' => Yii::t('app', 'Search'), 'url' => '#'],
];
?>
<div class="workflow-rule-definition-index">

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['class' => 'serial-number']
            ],
            [
                'attribute' => 'ordering',
                'contentOptions' => ['class' => 'ordering'],
            ],
            [
                'attribute' => 'rule.name',
            ],
            [
                'attribute' => 'user.username',
                'contentOptions' => ['class' => 'username'],
                'label' => '审核人',
            ],
            [
                'attribute' => 'userGroup.name',
                'contentOptions' => ['class' => 'user-group-name'],
            ],
            [
                'attribute' => 'enabled',
                'format' => 'boolean',
                'contentOptions' => ['class' => 'boolean pointer enabled-handler'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'headerOptions' => ['class' => 'last'],
                'contentOptions' => ['class' => 'btn-2']
            ],
        ],
    ]);
    ?>

</div>

<?php
$this->registerJs('yadjet.actions.toggle("table td.enabled-handler img", "' . Url::toRoute('toggle') . '");');
