<?php

use app\models\Option;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AdSpaceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside search-form form-layout-column" style="display: none">
    <div class="ad-space-search form">

        <?php
        $form = ActiveForm::begin([
            'id' => 'form-search-ad-spaces',
            'action' => ['index'],
            'method' => 'get',
        ]);
        ?>

        <div class="entry">
            <?= $form->field($model, 'group_id')->dropDownList(\app\models\AdSpace::groupOptions(), ['prompt' => '']) ?>

            <?= $form->field($model, 'name') ?>
        </div>

        <div class="entry">
            <?= $form->field($model, 'enabled')->dropDownList(Option::booleanOptions(), ['prompt' => '']) ?>
        </div>

        <div class="form-group buttons">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
