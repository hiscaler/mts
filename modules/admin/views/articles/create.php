<?php
/* @var $this yii\web\View */
/* @var $model app\models\Article */

$this->title = Yii::t('app', 'Create {modelClass}', [
        'modelClass' => Yii::t('model', 'Article'),
    ]);

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Articles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
];
?>
<div class="article-create"> 

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
