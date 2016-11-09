<div id="banners">
    <?php
    $baseUrl = Yii::$app->getRequest()->getBaseUrl();
    foreach ($items as $item):
        ?>
        <a href="javascript:;"><img src="<?= $baseUrl . $item['picture'] ?>" alt=""></a>
    <?php endforeach; ?>
</div>

<?php
$this->registerCssFile($baseUrl . '/coin-slider/coin-slider-styles.css');
$this->registerJsFile($baseUrl . '/coin-slider/coin-slider.js', [
    'depends' => 'yii\web\JqueryAsset',
]);
?>

<?php \app\components\JsBlock::begin() ?>
<script type="text/javascript">
    $(function () {
        $('#banners').coinslider({
            width: $(window).width(),
            height: 440,
            spw: 1,
            sph: 1,
            effect: 'swirl',
            navigation: true,
            links: false,
            prevText: '',
            nextText: ''
        });
    });
</script>
<?php \app\components\JsBlock::end() ?>