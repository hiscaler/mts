<?php

use app\models\Option;
use app\models\Tenant;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model WorkflowRuleDefinition */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside">
    <div class="workflow-rule-definition-form form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'ordering')->dropDownList(Option::orderingOptions()) ?>

        <?= $form->field($model, 'user_id')->dropDownList(Tenant::users(), ['prompt' => '']) ?>

        <?= $form->field($model, 'user_group_id')->dropDownList(Tenant::userGroups(), ['prompt' => '']) ?>

        <?= $form->field($model, 'enabled')->checkbox([], false) ?>

        <div class="form-group buttons">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
