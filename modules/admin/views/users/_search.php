<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="form-outside search-form form-layout-column" style="display: none">
    <div class="user-search form">

        <?php
        $form = ActiveForm::begin([
                    'id' => 'form-user-search',
                    'action' => ['index'],
                    'method' => 'get',
        ]);
        ?>

        <div class="entry">
            <?= $form->field($model, 'username') ?>

            <?php echo $form->field($model, 'nickname') ?>
        </div>

        <div class="entry">
            <?php  // echo $form->field($model, 'role')->dropDownList(User::roleOptions(), ['prompt' => '']) ?>

            <?php echo $form->field($model, 'status')->dropDownList(User::statusOptions(), ['prompt' => '']) ?>
        </div>

        <div class="form-group buttons">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
