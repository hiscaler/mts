<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
defined('MTS_SDK_TENANT_ID') or define('MTS_SDK_TENANT_ID', 1);

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

$application = (new yii\web\Application($config));

$urlRulesCacheKey = 'url-rules-cache-key';
$cache = Yii::$app->getCache();
$urlRules = $cache->get($urlRulesCacheKey);
if ($urlRules === false) {
    // URL 规则处理
    $urlRules = \yadjet\mts\sdk\ApplicationGetter::urlRules();
    $urlRules = array_merge($urlRules, [
        '<controller:\w+>/' => '<controller>/index',
        '<controller>/<id:\d+>' => 'controller/view',
        '<controller:\w+>/<id:\w+>.html' => '<controller>/view'
    ]);

    $cache->set($urlRulesCacheKey, $urlRules, 86400);
}

Yii::$app->getUrlManager()->addRules($urlRules);
$application->run();
