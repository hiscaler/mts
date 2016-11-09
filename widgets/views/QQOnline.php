<div id="rightArrow"><a href="javascript:;" title="<?= Yii::t('site', 'Online service') ?>"></a></div>
<div id="floatDivBoxs">
    <div class="floatDtt"><?= Yii::t('site', 'Online service') ?></div>
    <div class="floatShadow">
        <ul class="floatDqq">
            <?php
            $baseUrl = Yii::$app->getRequest()->getBaseUrl();
            foreach ($items as $key => $value):
                ?>
                <li style="padding-left:0px;"><a target="_blank" href="tencent://message/?uin=<?= $key ?>&Site=shop.fgoing.com&Menu=yes"><img src="<?= $baseUrl ?>/images/qq.png" align="absmiddle">&nbsp;&nbsp;<?= $value ?></a></li>
            <?php endforeach; ?>
        </ul>
        <div class="floatDtxt">联系电话</div>
        <div class="floatDtel">0731-12345678</div>
    </div>
    <div class="floatDbg"></div>
</div>

<?php \app\components\JsBlock::begin() ?>
<script type="text/javascript">
    $(function () {
        var flag = 1;
        $('#rightArrow').click(function () {
            if (flag == 1) {
                $("#floatDivBoxs").animate({right: '-175px'}, 300);
                $(this).animate({right: '-5px'}, 300);
                $(this).css('background-position', '-50px 0');
                flag = 0;
            } else {
                $("#floatDivBoxs").animate({right: '0'}, 300);
                $(this).animate({right: '170px'}, 300);
                $(this).css('background-position', '0px 0');
                flag = 1;
            }
        });
    });
</script>
<?php \app\components\JsBlock::end() ?>