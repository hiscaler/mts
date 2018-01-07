<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ClassicCase */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => Yii::t('model', 'Classic Case'),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('model', 'Classic Case'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']]
];
?>
<div class="classic-case-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
