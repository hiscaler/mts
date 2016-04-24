<?php
/* @var $this yii\web\View */
/* @var $model app\models\Archive */

$this->title = Yii::t('app', 'Create Archive');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Archives'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index', 'modelName' => $modelName]],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create', 'modelName' => $modelName]]
];
?>
<div class="archive-create">

    <?=
    $this->render('_form', [
        'modelName' => $modelName,
        'model' => $model,
        'contentModel' => $contentModel,
    ])
    ?>

</div>
