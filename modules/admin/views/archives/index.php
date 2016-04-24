<?php

use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ArchiveSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Archives');
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index', 'modelName' => $modelName]],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create', 'modelName' => $modelName]],
    ['label' => Yii::t('app', 'Search'), 'url' => '#'],
];
?>
<div class="archive-index">

    <?php Pjax::begin(); ?>    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'node_id',
            'model_name',
            'title',
            'keyword',
            // 'description:ntext',
            // 'tags',
            // 'has_thumbnail',
            // 'thumbnail',
            // 'author',
            // 'source',
            // 'status',
            // 'enabled',
            // 'published_datetime:datetime',
            // 'clicks_count',
            // 'enabled_comment',
            // 'comments_count',
            // 'ordering',
            // 'tenant_id',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',
            // 'deleted_at',
            // 'deleted_by',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>
    <?php Pjax::end(); ?></div>
