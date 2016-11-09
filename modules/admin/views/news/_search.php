<?php

use app\models\Option;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\NewsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside search-form form-layout-column" style="display: none">
    <div class="news-search form">

        <?php
        $form = ActiveForm::begin([
                    'id' => 'form-news',
                    'action' => ['index'],
                    'method' => 'get',
        ]);
        ?>

        <div class="entry">            
            <?= $form->field($model, 'id') ?>

            <?= $form->field($model, 'title') ?>
        </div>

        <div class="entry">
            <?= $form->field($model, 'category_id')->dropDownList(\app\models\Category::getTree(\app\models\Lookup::getValue('m.models.category.type.news', 0)), ['prompt' => '', 'multiple' => 'multiple']) ?>

            <?= $form->field($model, 'entityAttributeId')->dropDownList(\app\models\Label::getItems(true), ['prompt' => '', 'multiple' => 'multiple'])->label(Yii::t('app', 'Entity Attributes')) ?>
        </div>
        
        <div class="entry">            
            <?= $form->field($model, 'author') ?>

            <?= $form->field($model, 'source') ?>
        </div>

        <div class="entry">
            <?= $form->field($model, 'status')->dropDownList(Option::statusOptions(), ['prompt' => '']) ?>

            <?= $form->field($model, 'enabled')->dropDownList(Option::booleanOptions(), ['prompt' => '']) ?>
        </div>

        <div class="form-group buttons">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

<?php
$baseUrl = Yii::$app->getRequest()->getBaseUrl();
$this->registerJsFile($baseUrl . '/chosen/chosen.jquery.min.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerCssFile($baseUrl . '/chosen/chosen.min.css');
$js = <<<EOT
$('#newssearch-category_id, #newssearch-entityattributeid').chosen({
    no_results_text: '无匹配节点：',
    placeholder_text_multiple: '点击此处，在空白框内输入或选择节点名称',
    width: '80%',
    search_contains: true,
    allow_single_deselect: true
});
EOT;
$this->registerJs($js);
