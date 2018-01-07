<?php

/* @var $this yii\web\View */
/* @var $model app\models\ClassicCase */

$this->title = 'Update Classic Case: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Classic Cases'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'View'), 'url' => ['view', 'id' => $model->id]],
];
?>
<div class="classic-case-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
