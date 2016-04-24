<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Option;

/* @var $this yii\web\View */
/* @var $model app\models\GroupOptionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside search-form form-layout-column" style="display: none">
    <div class="group-option-search form">

        <?php
        $form = ActiveForm::begin([
                    'id' => 'form-group-options-search',
                    'action' => ['index'],
                    'method' => 'get',
        ]);
        ?>

        <div class="entry">
            <?= $form->field($model, 'group_name')->dropDownList(\common\models\GroupOption::getGroupNames(), ['prompt' => '']) ?>

            <?= $form->field($model, 'alias') ?>
        </div>

        <div class="entry">
            <?= $form->field($model, 'enabled')->dropDownList(Option::booleanOptions(), ['prompt' => '']) ?>

            <?= $form->field($model, 'defaulted')->dropDownList(Option::booleanOptions(), ['prompt' => '']) ?>
        </div>

        <div class="form-group buttons">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
