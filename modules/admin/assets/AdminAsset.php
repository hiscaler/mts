<?php

namespace app\modules\admin\assets;

use yii\web\AssetBundle;

/**
 * @author hiscaler <hiscaler@gmail.com>
 */
class AdminAsset extends AssetBundle
{

    public $basePath = '@webroot';
    public $baseUrl = '@web/admin';
    public $css = [
        'css/application.css',
        'css/common.css',
        'css/widget-grid-view.css',
        'css/form.css',
        'css/art-dialog-twitter-skin.css',
        'layer/skin/layer.css',
        'layer/skin/layer.ext.css',
    ];
    public $js = [
        'js/doT.min.js',
        'js/jquery.artDialog.min.js',
        'js/artDialog.plugins.min.js',
        'layer/layer.min.js',
        'layer/extend/layer.ext.js',
        'js/application.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];

}
