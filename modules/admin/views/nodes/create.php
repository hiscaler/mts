<?php
/* @var $this yii\web\View */
/* @var $model common\models\Node */

$this->title = Yii::t('app', 'Create {modelClass}', [
            'modelClass' => Yii::t('model', 'Node'),
        ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Nodes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
];
?>
<div class="node-create">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
