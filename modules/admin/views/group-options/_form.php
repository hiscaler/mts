<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\GroupOption */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside">
    <div class="group-option-form form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'group_name')->textInput(['maxlength' => 255, 'class' => 'g-text g-text-medium']) ?>

        <?= $form->field($model, 'text')->textInput(['maxlength' => 255, 'class' => 'g-text g-text-medium']) ?>

        <?= $form->field($model, 'value')->textInput(['maxlength' => 255, 'class' => 'g-text g-text-medium']) ?>

        <?= $form->field($model, 'alias')->textInput(['maxlength' => 255, 'class' => 'g-text g-text-medium']) ?>

        <?= $form->field($model, 'enabled')->checkbox([], false) ?>

        <?= $form->field($model, 'defaulted')->checkbox([], false) ?>

        <?= $form->field($model, 'description')->textInput(['maxlength' => 255, 'class' => 'g-text g-text-medium']) ?>

        <?= $form->field($model, 'ordering')->dropDownList(common\models\Option::orderingOptions()) ?>

        <div class="form-group buttons">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
