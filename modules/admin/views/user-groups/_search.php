<?php

use app\models\Option;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TenantUserGroupSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside search-form form-layout-column" style="display: none">
    <div class="tenant-user-group-search form">

        <?php
        $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
        ]);
        ?>

        <div class="entry">
            <?= $form->field($model, 'alias') ?>

            <?= $form->field($model, 'name') ?>
        </div>

        <?= $form->field($model, 'enabled')->dropDownList(Option::booleanOptions(), ['prompt' => '']) ?>

        <div class="form-group buttons">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
