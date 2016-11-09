<?php

use app\models\Option;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$baseUrl = Yii::$app->getRequest()->getBaseUrl();

$this->params['breadcrumbs'][] = Yii::t('app', 'News');

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Grid Column Config'), 'url' => ['grid-column-configs/index', 'name' => 'app-models-News'], 'htmlOptions' => ['class' => 'grid-column-config', 'data-reload-object' => 'grid-view-news']],
    ['label' => Yii::t('app', 'Search'), 'url' => '#'],
];
?>
<div class="news-index">

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    Pjax::begin([
        'formSelector' => '#form-news-search',
    ]);
    $offsetTimestamp = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
    echo app\modules\admin\components\GridView::widget([
        'id' => 'grid-view-news',
        'name' => 'app-models-News',
        'rowOptions' => function ($model, $key, $index, $grid) use ($offsetTimestamp) {
            return [
                'class' => $model['updated_at'] >= $offsetTimestamp ? 'today' : 'previous',
            ];
        },
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
                'attribute' => 'category_id',
                'value' => function ($model) {
                    return $model['category']['name'];
                },
                'contentOptions' => ['class' => 'category-name'],
            ],
            [
                'attribute' => 'title',
                'format' => 'raw',
                'value' => function ($model) {
                    $output = "<span class=\"pk\">[ {$model['id']} ]</span>" . Html::a($model['title'], ['news/update', 'id' => $model['id']], ['class' => $model['is_picture_news'] ? 'picture' : '']);
                    $words = [];
                    foreach ($model['customeAttributes'] as $attr) {
                        $words[] = $attr['name'];
                    }
                    $sentence = Inflector::sentence($words, '、', null, '、');
                    if (!empty($sentence)) {
                        $sentence = "<span class=\"attributes\">{$sentence}</span>";
                    }

                    return $sentence . $output;
                },
            ],
            'keywords',
            'tags',
            'author',
            'source',
            [
                'attribute' => 'status',
                'format' => 'dataStatus',
                'contentOptions' => ['class' => 'data-status']
            ],
            [
                'attribute' => 'enabled',
                'format' => 'boolean',
                'contentOptions' => ['class' => 'boolean news-enabled-handler pointer']
            ],
            [
                'attribute' => 'enabled_comment',
                'format' => 'boolean',
                'contentOptions' => ['class' => 'boolean news-enabled-comment-handler pointer']
            ],
            [
                'attribute' => 'comments_count',
                'contentOptions' => ['class' => 'number']
            ],
            [
                'attribute' => 'clicks_count',
                'contentOptions' => ['class' => 'number']
            ],
            [
                'attribute' => 'up_count',
                'contentOptions' => ['class' => 'number']
            ],
            [
                'attribute' => 'down_count',
                'contentOptions' => ['class' => 'number']
            ],
            [
                'attribute' => 'published_at',
                'format' => 'date',
                'contentOptions' => ['class' => 'date']
            ],
            [
                'attribute' => 'created_by',
                'value' => function ($model) {
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
                'value' => function ($model) {
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
                'value' => function ($model) {
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
                'template' => '{view} {entityAttributes} {update} {delete} {undo}',
                'buttons' => [
                    'entityAttributes' => function ($url, $model, $key) use ($baseUrl) {
                        return Html::a(Html::img($baseUrl . '/images/attributes.png'), ['entity-attributes/index', 'entityId' => $model['id'], 'entityName' => 'common-models-News'], ['title' => Yii::t('app', 'Entity Attributes'), 'class' => 'setting-entity-attributes', 'data-pjax' => '0']);
                    },
                    'delete' => function ($url, $model, $key) {
                        return $model['status'] != Option::STATUS_DELETED ? Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('yii', 'Delete'),
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]) : '';
                    },
                    'undo' => function ($url, $model, $key) {
                        return $model['status'] == Option::STATUS_DELETED ? Html::a('<span class="glyphicon glyphicon-undo"></span>', $url, [
                            'title' => Yii::t('app', 'Undo'),
                            'data-confirm' => Yii::t('app', 'Are you sure you want to undo this item?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]) : '';
                    }
                ],
                'headerOptions' => ['class' => 'last'],
                'contentOptions' => ['class' => 'btn-4'],
            ],
        ],
    ]);
    Pjax::end();
    ?>

</div>

<?php
$this->registerJs('yadjet.actions.toggle("table td.news-enabled-handler img", "' . Url::toRoute('toggle') . '");');
$this->registerJs('yadjet.actions.toggle("table td.news-enabled-comment-handler img", "' . Url::toRoute('toggle-comment') . '");');

$js = <<<'EOT'
jQuery(document).on('click', 'a.setting-entity-attributes', function () {
    var $this = $(this);
    $.ajax({
        type: 'GET',
        url: $this.attr('href'),
        beforeSend: function(xhr) {
            $.fn.lock();
        }, success: function(response) {
            layer.open({
                title: $this.attr('title'),
                content: response,
                lock: true,
                padding: '10px'
            });
            $.fn.unlock();
        }, error: function(XMLHttpRequest, textStatus, errorThrown) {
            $.alert('[ ' + XMLHttpRequest.status + ' ] ' + XMLHttpRequest.responseText);
            $.fn.unlock();
        }
    });

    return false;
});
EOT;
$this->registerJs($js);
