<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Option;

/* @var $this yii\web\View */
/* @var $model app\models\SlideSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside search-form form-layout-column" style="display: none">
    <div class="slide-search form">

        <?php
        $form = ActiveForm::begin([
                'id' => 'form-search-slides',
                'action' => ['index'],
                'method' => 'get',
        ]);
        ?>

        <div class="entry">
            <?= $form->field($model, 'group_id')->dropDownList(\app\models\Slide::groupOptions(), ['prompt' => '']) ?>

            <?= $form->field($model, 'title') ?>
        </div>

        <div class="entry">
            <?= $form->field($model, 'enabled')->dropDownList(Option::booleanOptions(), ['prompt' => '']) ?>

            <?= $form->field($model, 'status')->dropDownList(Option::statusOptions(), ['prompt' => '']) ?>
        </div>

        <div class="form-group buttons">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
