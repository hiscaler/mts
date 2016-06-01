<?php

use yii\widgets\Breadcrumbs;

$this->beginContent('@app/modules/admin/views/layouts/base.php');
?>

<div class="layout grid-s6m0e0">
    <div class="col-main">
        <div class="main-wrap">
            <?php
            if (isset($this->params['breadcrumbs']) && $this->params['breadcrumbs']) {
                echo '<div class="breadcrumbs clearfix">'
                . Breadcrumbs::widget(
                    [
                        'itemTemplate' => "<li>{link}<i>&raquo;</i></li>",
                        'homeLink' => [
                            'label' => Yii::t('app', 'Homepage'),
                            'url' => ['default/index'],
                        ],
                        'links' => $this->params['breadcrumbs'],
                ])
                . '</div>';
            }
            if (isset($this->params['menus']) && $this->params['menus']) {
                echo app\modules\admin\widgets\MenuButtons::widget([
                    'items' => $this->params['menus'],
                ]);
            }
            ?>
            <div class="container">
                <div class="inner">
                    <?= $content; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sub">
        <?= \app\modules\admin\widgets\MyControlPanel::widget(); ?>
    </div>
</div>

<?php $this->endContent(); ?>