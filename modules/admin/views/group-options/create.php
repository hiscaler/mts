<?php
/* @var $this yii\web\View */
/* @var $model app\models\GroupOption */

$this->title = Yii::t('app', 'Create {modelClass}', [
            'modelClass' => Yii::t('model', 'Group Option'),
        ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Group Options'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
];
?>
<div class="group-option-create">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
