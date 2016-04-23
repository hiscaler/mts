<?php

use app\models\Option;
use app\models\Node;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\NodeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside search-form form-layout-column" style="display: none">
    <div class="node-search form">

        <?php
        $form = ActiveForm::begin([
                    'id' => 'form-nodes-search',
                    'action' => ['index'],
                    'method' => 'get',
        ]);
        ?>

        <div class="entry">
            <?= $form->field($model, 'alias') ?>

            <?= $form->field($model, 'name') ?>
        </div>

        <div class="entry">
            <?= $form->field($model, 'parent_id')->dropDownList(Node::parentOptions(false), ['prompt' => '']) ?>

            <?=
            $form->field($model, 'enabled')->dropDownList(Option::booleanOptions(), [
                'prompt' => ''
            ])
            ?>
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
$('#nodesearch-parent_id').chosen({
    no_results_text: '无匹配节点：',
    placeholder_text_single: '点击此处，在空白框内输入或选择节点名称',
    width: '80%',
    search_contains: true,
    allow_single_deselect: true
});
EOT;
$this->registerJs($js);
