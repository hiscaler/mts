<?php
/* @var $this yii\web\View */
/* @var $model common\models\Node */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
            'modelClass' => Yii::t('model', 'Node'),
        ]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Nodes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->name . ' [ ' . Yii::t('app', 'Update') . ' ]';

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
];
?>
<div class="node-update">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
