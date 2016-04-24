<?php
/* @var $this yii\web\View */
/* @var $model app\models\Archive */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
        'modelClass' => 'Archive',
    ]) . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Archives'), 'url' => ['index', 'modelName' => $modelName]];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index', 'modelName' => $model->model_name]],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create', 'modelName' => $model->model_name]],
];
?>
<div class="archive-update">

    <?=
    $this->render('_form', [
        'modelName' => $modelName,
        'model' => $model,
    ])
    ?>

</div>
