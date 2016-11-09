<div id="navigate">
    <div class="inner">
        <div class="login-informations">
            <?php
            $user = Yii::$app->getUser();
            if (!$user->isGuest):
                ?>
                欢迎您，<em><?= Yii::$app->getUser()->getIdentity()->username ?></em>
                [ <a href="<?= yii\helpers\Url::toRoute(['/site/logout']) ?>">退出</a> ]
            <?php endif; ?>
        </div>
        <div class="member-menus">
            <?php if ($user->isGuest): ?>
                <a class="signup" href="<?= \yii\helpers\Url::toRoute(['/site/signup']) ?>">注册</a>|
                <a class="signin" href="<?= \yii\helpers\Url::toRoute(['/site/signin']) ?>">登陆</a>|
            <?php endif; ?>
            <a href="<?= \yii\helpers\Url::toRoute(['/member/default/index']) ?>">会员中心</a>|
            <a href="<?= \yii\helpers\Url::toRoute(['/member/orders/index']) ?>">我的订单</a>
        </div>
    </div>
</div>