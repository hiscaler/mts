<?php
/* @var $this yii\web\View */
/* @var $model app\models\TenantUserGroup */

$this->title = Yii::t('app', 'Create {modelClass}', [
        'modelClass' => Yii::t('model', 'Tenant User Group'),
    ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
];
?>
<div class="tenant-user-group-create">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
