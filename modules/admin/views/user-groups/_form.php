<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TenantUserGroup */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside">
    <div class="tenant-user-group-form form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'alias')->textInput(['maxlength' => 20]) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => 30]) ?>

        <?= $form->field($model, 'enabled')->checkbox([], false) ?>

        <div class="form-group buttons">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
