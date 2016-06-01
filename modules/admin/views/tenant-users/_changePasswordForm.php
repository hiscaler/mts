<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside">
    <div class="form user-form">

        <?php $form = ActiveForm::begin(); ?>       

        <?= $form->field($user, 'username')->textInput(['maxlength' => 255, 'readonly' => 'readonly']) ?>

        <?= $form->field($model, 'password')->passwordInput(['maxlength' => 255, 'class' => 'g-text']) ?>

        <?= $form->field($model, 'confirmPassword')->passwordInput(['maxlength' => 255, 'class' => 'g-text']) ?>

        <div class="form-group buttons">
            <?= Html::submitButton(Yii::t('app', 'Change Password'), ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>