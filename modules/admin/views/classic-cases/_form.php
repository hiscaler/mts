<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ClassicCase */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="form-outside form-layout-column">
    <div class="classic-case-form form">
        <?php
        $form = ActiveForm::begin([
            'options' => [
                'enctype' => 'multipart/form-data',
            ],
        ]);
        ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'tags')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'keywords')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'picture_path')->fileInput() ?>

        <?=
        \yadjet\editor\UEditor::widget([
            'form' => $form,
            'model' => $model,
            'attribute' => 'content',
        ])
        ?>

        <?= $form->field($model, 'enabled')->checkbox() ?>

        <?= $form->field($model, 'ordering')->textInput() ?>

        <?=
        \yadjet\datePicker\my97\DatePicker::widget([
            'form' => $form,
            'model' => $model,
            'attribute' => 'published_at',
            'pickerType' => 'datetime',
        ]);
        ?>
        <div class="form-group buttons">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
