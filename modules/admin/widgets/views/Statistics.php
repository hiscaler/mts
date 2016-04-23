<div id="statistics-char"></div>

<?php
$js = <<<JS
$('#statistics-char').highcharts({
    chart: {
        type: 'areaspline'
    },
    title: {
        text: '[ $tenantName ] 二十四小时发布统计'
    },
    legend: {
        layout: 'vertical',
        align: 'left',
        verticalAlign: 'top',
        x: 120,
        y: 120,
        floating: true,
        borderWidth: 1,
        backgroundColor: '#FFFFFF'
    },
    xAxis: {
        categories: [
            '0',
            '1',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7',
            '8',
            '9',
            '10',
            '11'
        ]
    },
    yAxis: {
        title: {
            text: '发布条数'
        }
    },
    tooltip: {
        shared: true,
        valueSuffix: ' 条'
    },
    credits: {
        enabled: false
    },
    plotOptions: {
        areaspline: {
            fillOpacity: 0.5
        }
    },
    series: $data
});
JS;

$this->registerJsFile('@web/js/highcharts.min.js', [
    'depends' => ['yii\web\JqueryAsset']
]);
$this->registerJs($js);

