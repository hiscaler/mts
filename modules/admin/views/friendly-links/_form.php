<?php

use app\models\FriendlyLink;
use app\models\Option;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FriendlyLink */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="form-outside form-layout-column">
    <div class="friendly-link-form form">

        <?php
        $form = ActiveForm::begin([
                'options' => [
                    'enctype' => 'multipart/form-data',
                ]
        ]);
        ?>

        <div class="entry">
            <?= $form->field($model, 'group_id')->dropDownList(FriendlyLink::groupOptions(), ['prompt' => '']) ?>

            <?= $form->field($model, 'type')->dropDownList(FriendlyLink::typeOptions()) ?>
        </div>

        <?= $form->field($model, 'title')->textInput(['maxlength' => 255, 'class' => 'g-text g-text-large']) ?>

        <?= $form->field($model, 'description')->textInput(['maxlength' => 255, 'class' => 'g-text g-text-large']) ?>

        <?= $form->field($model, 'url')->textInput(['maxlength' => 255, 'class' => 'g-text g-text-large']) ?>

        <div class="entry">
            <?= $form->field($model, 'url_open_target')->dropDownList(FriendlyLink::urlOpenTargetOptions()) ?>

            <?php
            $imagePreview = null;
            $imagePath = $model->logo_path;
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
            echo $form->field($model, 'logo_path', [
                'template' => "{label}\n{input}{$imagePreview}\n{hint}\n{error}"
            ])->fileInput()
            ?>
        </div>

        <div class="entry">
            <?= $form->field($model, 'ordering')->textInput(['class' => 'g-text g-text-number']) ?>

            <?= $form->field($model, 'status')->dropDownList(Option::statusOptions(), ['prompt' => '']) ?>
        </div>

        <?= $form->field($model, 'enabled')->checkbox([], null) ?>

        <div class="form-group buttons">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
