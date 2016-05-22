<?php
/* @var $this yii\web\View */
/* @var $model app\models\Slide */

$this->title = Yii::t('app', 'Create {modelClass}', [
            'modelClass' => Yii::t('model', 'Slide'),
        ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Slides'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
];
?>
<div class="slide-create">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
