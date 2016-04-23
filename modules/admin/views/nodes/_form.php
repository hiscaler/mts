<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Option;
use app\models\Node;

/* @var $this yii\web\View */
/* @var $model app\models\Node */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside">
    <div class="node-form form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'alias')->textInput(['maxlength' => 255, 'class' => 'g-text g-text-medium']) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => 255, 'class' => 'g-text g-text-medium']) ?>

        <?= $form->field($model, 'model_name')->dropDownList(Option::modelNameOptions(), ['prompt' => '']) ?>

        <?= $form->field($model, 'parameters')->textarea(['class' => 'g-text-area']) ?>

        <?= $form->field($model, 'parent_id')->dropDownList(Node::parentOptions()) ?>

        <?= $form->field($model, 'ordering')->dropDownList(Option::orderingOptions(0, 100)) ?>

        <?= $form->field($model, 'enabled')->checkBox([], false) ?>

        <?= $form->field($model, 'entity_status')->dropDownList(Node::entityStatusOptions(), ['prompt' => '']) ?>

        <?= $form->field($model, 'entity_enabled')->checkBox([], false) ?>

        <div class="form-group buttons">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

<?php
$baseUrl = Yii::$app->getRequest()->getBaseUrl();
$this->registerJsFile($baseUrl . '/chosen/chosen.jquery.min.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerCssFile($baseUrl . '/chosen/chosen.min.css');
$js = <<<EOT
$('#node-parent_id').chosen({
    no_results_text: '无匹配节点：',
    placeholder_text_single: '点击此处，在空白框内输入或选择节点名称',
    width: '80%',
    search_contains: true,
    allow_single_deselect: true
});
EOT;
$this->registerJs($js);