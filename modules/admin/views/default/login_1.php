<?php

use yii\captcha\Captcha;
use yii\widgets\ActiveForm;

$baseUrl = Yii::$app->getRequest()->getBaseUrl() . '/login';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="Keywords" content="" />   
        <meta name="description" content="" />     
        <title>后台登陆界面</title>  
        <link href="<?= $baseUrl ?>/css/base.css" type="text/css" rel="stylesheet" />     
        <link href="<?= $baseUrl ?>/css/login.css" type="text/css" rel="stylesheet" />    
    </head>
    <body>
        <div class="login">
   　<h1><a href="http://apdnews.com/" class="logo" target="_blank" title="亚太日报主站"></a></h1>
            <?php
            $fieldConfigs = [
                'options' => ['class' => 'entry', 'tag' => 'div'],
                'template' => '{input}',
            ];
            $form = ActiveForm::begin([
                        'id' => 'login-form',
                        'enableAjaxValidation' => false,
            ]);
            echo $form->errorSummary($model);
            ?>             

            <?= $form->field($model, 'username', $fieldConfigs)->textInput(['class' => 'username', 'placeholder' => Yii::t('user', 'Username')]); ?>

            <?= $form->field($model, 'password', $fieldConfigs)->passwordInput(['class' => 'password', 'placeholder' => Yii::t('user', 'Password')]); ?>

            <?=
            $form->field($model, 'verifyCode', ['template' => '{input}'])->widget(Captcha::className(), [
                'template' => '<label class="code">{input}{image}</label>',
                'captchaAction' => 'default/captcha',
                'options' => ['placeholder' => '验证码',]
            ])->label(false);
            ?>

            <?=
            $form->field($model, 'rememberMe', [
                'template' => '{input}<label class="remember">记住我</label>'
            ])->checkbox(['class' => 'checkbox'], false)->label(false);
            ?>

            <input type="submit" name="bt_login" id="bt_login" value="登录" class="submit" />

            <?php ActiveForm::end(); ?>
        </div>
    </body>    
</html>