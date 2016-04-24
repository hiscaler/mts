<?php
/* @var $this yii\web\View */
/* @var $model app\models\GroupOption */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
            'modelClass' => Yii::t('model', 'Group Option'),
        ]) . ' ' . $model->text;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Group Options'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => "$model->group_name  ({$model->text})", 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'View'), 'url' => ['view', 'id' => $model->id]],
];
?>
<div class="group-option-update">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
