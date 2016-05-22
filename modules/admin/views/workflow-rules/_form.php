<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\WorkflowRule */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside">
    <div class="workflow-rule-form form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'enabled')->checkbox([], null) ?>

        <div class="form-group buttons">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
