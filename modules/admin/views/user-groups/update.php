<?php
/* @var $this yii\web\View */
/* @var $model app\models\TenantUserGroup */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
            'modelClass' => Yii::t('model', 'Tenant User Group'),
        ]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update') . ' [ ' . $model->name . ' ]';

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
];
?>
<div class="tenant-user-group-update">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
