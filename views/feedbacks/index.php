<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = '客户留言';
$this->params['breadcrumbs'][] = $this->title;

$session = Yii::$app->getSession();
?>

<div class="feedbacks">

    <?php if ($session->hasFlash('success')): ?>

        <div class="notice">
            <?= $session->getFlash('success') ?>
        </div>
    <?php else: ?>

        <div class="form-outside">
            <div class="download-form form">

                <?php
                $form = ActiveForm::begin();
                ?>

                <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'tel')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'style' => 'width: 500px']) ?>

                <?= $form->field($model, 'message')->textarea(['style' => 'width: 500px;']) ?>

                <div class="form-group buttons">
                    <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>

    <?php endif; ?>

</div>