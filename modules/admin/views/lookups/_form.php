<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Lookup;

/* @var $this yii\web\View */
/* @var $model app\models\Lookup */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside">
    <div class="form lookup-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'label')->textInput(['maxlength' => 255, 'class' => 'g-text-large']) ?>

        <?= $form->field($model, 'description')->textInput(['maxlength' => 255, 'class' => 'g-text-large']) ?>

        <?= $form->field($model, 'value')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'return_type')->dropDownList(Lookup::returnTypeOptions()) ?>

        <?= $form->field($model, 'enabled')->checkbox([], false) ?>

        <div class="form-group buttons">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-create' : 'btn btn-update']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
