<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Slide;

/* @var $this yii\web\View */
/* @var $model app\models\Slide */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside">
    <div class="slide-form form">

        <?php
        $form = ActiveForm::begin([
                'options' => [
                    'enctype' => 'multipart/form-data',
                ],
        ]);
        ?>

        <?= $form->field($model, 'group_id')->dropDownList(Slide::groupOptions()) ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => 255, 'class' => 'g-text g-text-mediual']) ?>

        <?= $form->field($model, 'url')->textInput(['maxlength' => 255]) ?>

        <?= $form->field($model, 'url_open_target')->dropDownList(Slide::urlOpenTargetOptions(), ['prompt' => '']) ?>

        <?php
        $imagePreview = null;
        $imagePath = $model->picture;
        if (!$model->isNewRecord && !empty($imagePath)) {
            if ($model->_fileUploadConfig['thumb']['generate']) {
                $linkOptions = [
                    'width' => $model->_fileUploadConfig['thumb']['width'],
                    'height' => $model->_fileUploadConfig['thumb']['height']
                ];
            } else {
                $linkOptions = [];
            }
            $imagePath = Yii::$app->getRequest()->getBaseUrl() . $imagePath;
            $imagePreview = Html::a(Html::img($imagePath, $linkOptions), $imagePath, ['target' => '_blank']);
            $imagePreview = Html::tag('div', $imagePreview, ['class' => 'image-preview']);
        }
        echo $form->field($model, 'picture', [
            'template' => "{label}\n{input}{$imagePreview}\n{hint}\n{error}"
        ])->fileInput()
        ?>

        <?= $form->field($model, 'status')->dropDownList(\app\models\Option::statusOptions(), ['prompt' => '']) ?>

        <?= $form->field($model, 'enabled')->checkbox([], null) ?>

        <?= $form->field($model, 'ordering')->textInput(['class' => 'g-text g-text-number']) ?>

        <div class="form-group buttons">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
