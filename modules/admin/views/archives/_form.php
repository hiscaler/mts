<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Archive */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside form-layout-column">
    <div class="friendly-link-form form">

        <?php
        $form = ActiveForm::begin([
                'options' => [
                    'enctype' => 'multipart/form-data',
                ],
        ]);

        echo $form->errorSummary($model);
        ?>

        <?= $form->field($model, 'node_id')->dropDownList(app\models\Node::hashMapItems($modelName), ['prompt' => '']) ?>

        <?php
        $entityAttributes = app\models\Label::getItems(false, true);
        if ($entityAttributes):
            ?>
            <fieldset>
                <legend><?= Yii::t('app', 'Entity Attributes') ?></legend>
                <?php
                foreach ($entityAttributes as $key => $attributes) {
                    echo $form->field($model, 'ownerLabels')->checkboxList($attributes, ['unselect' => null])->label($key);
                }
                ?>
            </fieldset>
        <?php endif; ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <div class="entry">
            <?= $form->field($model, 'keyword')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'tags')->textInput(['maxlength' => true]) ?>
        </div>

        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

        <?=
        \yadjet\editor\UEditor::widget([
            'form' => $form,
            'model' => $contentModel,
            'attribute' => 'content',
        ])
        ?>

        <?= $form->field($model, 'thumbnail')->textInput(['maxlength' => true]) ?>

        <div class="entry">
            <?= $form->field($model, 'author')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'source')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="entry">
            <?= $form->field($model, 'published_datetime')->textInput() ?>

            <?= $form->field($model, 'ordering')->textInput(['value' => 0]) ?>
        </div>

        <div class="entry">
            <?= $form->field($model, 'enabled')->checkbox(false, null) ?>

            <?= $form->field($model, 'enabled_comment')->checkbox(false, null) ?>
        </div>

        <?= $form->field($model, 'status')->textInput() ?>

        <div class="form-group buttons">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
