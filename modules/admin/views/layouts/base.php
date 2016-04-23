<?php

use app\models\MTS;
use app\modules\admin\assets\AdminAsset;
use app\modules\admin\widgets\MainMenu;
use app\modules\admin\widgets\Toolbar;
use yii\helpers\Html;
use yii\widgets\Spaceless;

/* @var $this View */
/* @var $content string */

AdminAsset::register($this);
$baseUrl = Yii::$app->getRequest()->getBaseUrl() . '/admin';
$siteName = MTS::getTenantName();
if (YII_ENV == 'prod') {
    Spaceless::begin();
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language; ?>">
    <head>
        <meta charset="<?= Yii::$app->charset; ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags(); ?>
        <title>「Backend」<?= Html::encode($this->title) ?> | <?= $siteName ?></title>
        <?php $this->head(); ?>
    </head>
    <body>
        <?php $this->beginBody(); ?>
        <div id="page-hd">
            <div id="page">
                <!-- Header -->
                <div id="header">
                    <div id="logo"><?= Html::a(Html::img($baseUrl . '/images/logo.png'), ['default/index']); ?></div>
                    <div id="main-menu">
                        <?= MainMenu::widget(); ?>
                    </div>
                    <div id="header-account-manage">
                        <?= Toolbar::widget(); ?>
                    </div>
                </div>
                <!-- // Header -->
            </div>
        </div>
        <div id="page-bd">
            <div class="container">
                <?= $content; ?>
            </div>
        </div>
        <div id="page-ft">
            <div id="footer">
                Copyright &copy; <?= date('Y') ?> by <?= $siteName ?> All Rights Reserved.
            </div>
        </div>

        <?php $this->endBody(); ?>
    </body>
</html>
<?php
$this->registerJs('yadjet.icons.boolean = ["' . $baseUrl . '/images/no.png' . '", "' . $baseUrl . '/images/yes.png' . '"];');
$this->endPage();
if (YII_ENV == 'prod') {
    Spaceless::end();
}
?>