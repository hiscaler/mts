<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ArchiveSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside search-form form-layout-column" style="display: none">
    <div class="archive-search form">

        <?php
        $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
        ]);
        ?>

        <div class="entry">
            <div class="column">
                <?= $form->field($model, 'id') ?>
            </div>

            <div class="column">
                <?= $form->field($model, 'title') ?>
            </div>
        </div>

        <div class="entry">
            <div class="column">
                <?= $form->field($model, 'node_id')->dropDownList(\app\models\Node::parentOptions(false), ['prompt' => '']) ?>
            </div>

            <div class="column">
                <?= $form->field($model, 'model_name')->dropDownList(app\models\Option::modelNameOptions(), ['prompt' => '']) ?>
            </div>
        </div>

        <?php // echo $form->field($model, 'description') ?>

        <?php // echo $form->field($model, 'tags') ?>

        <?php // echo $form->field($model, 'has_thumbnail') ?>

        <?php // echo $form->field($model, 'thumbnail') ?>

        <?php // echo $form->field($model, 'author') ?>

        <?php // echo $form->field($model, 'source') ?>

        <?php // echo $form->field($model, 'status') ?>

        <?php // echo $form->field($model, 'enabled') ?>

        <?php // echo $form->field($model, 'published_datetime') ?>

        <?php // echo $form->field($model, 'clicks_count') ?>

        <?php // echo $form->field($model, 'enabled_comment') ?>

        <?php // echo $form->field($model, 'comments_count') ?>

        <?php // echo $form->field($model, 'ordering') ?>

        <?php // echo $form->field($model, 'tenant_id') ?>

        <?php // echo $form->field($model, 'created_at') ?>

        <?php // echo $form->field($model, 'created_by') ?>

        <?php // echo $form->field($model, 'updated_at') ?>

        <?php // echo $form->field($model, 'updated_by') ?>

        <?php // echo $form->field($model, 'deleted_at') ?>

        <?php // echo $form->field($model, 'deleted_by')  ?>

        <div class="form-group buttons">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
