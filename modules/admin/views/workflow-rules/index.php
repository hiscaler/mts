<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\WorkflowRuleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Workflow Rules');
$this->params['breadcrumbs'][] = $this->title;

$baseUrl = Yii::$app->getRequest()->baseUrl;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Search'), 'url' => '#'],
];
?>
<div class="workflow-rule-index">

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    Pjax::begin([
        'formSelector' => '#form-workflow-rules-search',
    ]);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['class' => 'serial-number']
            ],
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a($model['name'], ['update', 'id' => $model['id']]);
                },
                        'contentOptions' => ['class' => 'workflow-rule-name'],
                    ],
                    'description:ntext',
                    [
                        'attribute' => 'enabled',
                        'format' => 'boolean',
                        'contentOptions' => ['class' => 'boolean pointer enabled-handler'],
                    ],
                    [
                        'attribute' => 'created_by',
                        'value' => function($model) {
                            return $model['creater']['nickname'];
                        },
                        'contentOptions' => ['class' => 'username']
                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => 'date',
                        'contentOptions' => ['class' => 'date']
                    ],
                    [
                        'attribute' => 'updated_by',
                        'value' => function($model) {
                            return $model['updater']['nickname'];
                        },
                        'contentOptions' => ['class' => 'username rb-updated-by']
                    ],
                    [
                        'attribute' => 'updated_at',
                        'format' => 'date',
                        'contentOptions' => ['class' => 'date rb-updated-at']
                    ],
                    [
                        'attribute' => 'deleted_by',
                        'value' => function($model) {
                            return $model['deleter']['nickname'];
                        },
                        'contentOptions' => ['class' => 'username']
                    ],
                    [
                        'attribute' => 'deleted_at',
                        'format' => 'date',
                        'contentOptions' => ['class' => 'date']
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {definitions} {update} {delete}',
                        'buttons' => [
                            'definitions' => function ($url, $model, $key) use ($baseUrl) {
                                return Html::a(Html::img($baseUrl . '/images/rule-definitions.png'), ['workflow-rule-definitions/index', 'ruleId' => $model['id']], ['title' => Yii::t('app', Yii::t('app', 'Workflow Rule Definitions')), 'data-pjax' => '0']);
                            },
                                ],
                                'headerOptions' => ['class' => 'last'],
                                'contentOptions' => ['class' => 'btn-3'],
                            ],
                        ],
                    ]);
                    Pjax::end();
                    ?>

                </div>

                <?php
                $this->registerJs('yadjet.actions.toggle("table td.enabled-handler img", "' . Url::toRoute('toggle') . '");');
                