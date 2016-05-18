<?php

use yadjet\editor\UEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Article */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside">
    <div class="article-form form">

        <?php
        $form = ActiveForm::begin([
                'options' => [
                    'enctype' => 'multipart/form-data',
                ],
        ]);
        ?>

        <?= $form->field($model, 'alias')->textInput(['maxlength' => 255, 'class' => 'g-text g-text-large']) ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => 255, 'class' => 'g-text g-text-large']) ?>

        <?= $form->field($model, 'tags')->textInput(['maxlength' => 255, 'class' => 'g-text g-text-large']) ?>

        <?= $form->field($model, 'keywords')->textInput(['maxlength' => 255, 'class' => 'g-text g-text-large']) ?>

        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

        <?=
        UEditor::widget([
            'form' => $form,
            'model' => $model,
            'attribute' => 'content',
        ])
        ?>

        <?php
        $imagePreview = null;
        $imagePath = $model->picture_path;
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
            $imagePreview .= Html::a('x', ['remove-image', 'id' => $model->id], ['class' => 'btn-remove']);
            $imagePreview = Html::tag('div', $imagePreview, ['class' => 'image-preview']);
        }
        echo $form->field($model, 'picture_path', [
            'template' => "{label}\n{input}{$imagePreview}\n{hint}\n{error}"
        ])->fileInput()
        ?>

        <?= $form->field($model, 'ordering')->textInput(['class' => 'g-text g-text-number']) ?>

        <div class="form-group buttons">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

<?php
$js = <<<'EOT'
jQuery(document).on('click', 'a.btn-remove', function () {
    var $this = $(this);
    $.ajax({
        type: 'POST',
        url: $this.attr('href'),
        dataType: 'json',
        beforeSend: function(xhr) {
            $.fn.lock();
        }, success: function(response) {
            if (response.success) {
                $('.image-preview').remove();
            }
            $.fn.unlock();
        }, error: function(XMLHttpRequest, textStatus, errorThrown) {
            $.alert('[ ' + XMLHttpRequest.status + ' ] ' + XMLHttpRequest.responseText);
            $.fn.unlock();
        }
    });
    
    return false;
});
EOT;
$this->registerJs($js);

$js = <<<'EOT'
jQuery(document).on('click', 'a.dialog-choice-nodes', function () {
    var $this = $(this);
    $.ajax({
        type: 'GET',
        url: $this.attr('href'),
        data: {
            nodeIds: $('#article-entitynodeids').val()
        },
        beforeSend: function(xhr) {
            $.fn.lock();
        }, success: function(response) {
            $.dialog({
                title: '内容节点选择',
                content: response,
                lock: true,
                padding: '10px',
                ok: function () {
                    var choiceObject = $.fn.zTree.getZTreeObj("__ztree__").getCheckedNodes(true);
                    var ids = [], names = [];
                    $.each(choiceObject, function(i, o) {
                        ids.push(o.id);
                        names.push('<em>' + o.name + '</em>');
                    });
                    $('#article-entitynodeids').val(ids.toString());
                    $('#node-names').html(names.join(''));
                }
            });
            $.fn.unlock();
        }, error: function(XMLHttpRequest, textStatus, errorThrown) {
            $.alert('[ ' + XMLHttpRequest.status + ' ] ' + XMLHttpRequest.responseText);
            $.fn.unlock();
        }
    });
    
    return false;
});
EOT;
$this->registerJs($js);
