<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\GroupOptionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Group Options');
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Search'), 'url' => '#'],
];
?>
<div class="group-option-index">

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    Pjax::begin([
        'formSelector' => '#form-group-options-search',
    ]);
    $groupName = null;
    $rowOptions = [];
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions' => function ($model, $key, $index, $grid) use (&$groupName, &$rowOptions) {
            if ($index) {
                if ($model['group_name'] != $groupName) {
                    $groupName = $model['group_name'];
                    $rowOptions['class'] = 'block';
                }
                
                return $rowOptions;
            } else {
                $groupName = $model['group_name'];
            }
        },
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
                'attribute' => 'group_name',
                'contentOptions' => ['class' => 'group-options-group-name'],
            ],
            [
                'attribute' => 'value',
                'contentOptions' => ['class' => 'group-options-value center'],
            ],
            [
                'attribute' => 'text',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a($model['text'], ['update', 'id' => $model['id']]);
                },
                'contentOptions' => ['class' => 'group-options-text'],
            ],
            'alias',
            'description',
            [
                'attribute' => 'enabled',
                'format' => 'boolean',
                'contentOptions' => ['class' => 'boolean pointer enabled-handler'],
            ],
            [
                'attribute' => 'defaulted',
                'format' => 'boolean',
                'contentOptions' => ['class' => 'boolean'],
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
                'contentOptions' => ['class' => 'username']
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'date',
                'contentOptions' => ['class' => 'date']
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
