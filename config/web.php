<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'language' => 'zh-CN',
    'bootstrap' => ['log'],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
            'layout' => 'main',
        ],
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '%*()JH()U)I)_OU*()Y)(UJ)UJ',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => '\yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                ],
                'app' => [
                    'class' => '\yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'view' => [
            'defaultExtension' => 'twig',
            'renderers' => [
                'twig' => [
                    'class' => '\yii\twig\ViewRenderer',
                    'cachePath' => '@runtime/Twig/cache',
                    'options' => YII_DEBUG ? ['debug' => true, 'auto_reload' => true] : ['auto_reload' => true],
                    'extensions' => YII_DEBUG ? ['\Twig_Extension_Debug'] : [],
                    'globals' => [
                        'applicationHelper' => 'ApplicationHelperYii2',
                        'html' => '\yii\helpers\Html',
                        'stringHelper' => '\yii\helpers\StringHelper',
                        'formatter' => '\yii\i18n\Formatter',
                        'dumper' => '\yii\helpers\VarDumper',
                        'yii' => 'Yii',
                        'archiveGetter' => '\yadjet\mts\sdk\ArchiveGetter',
                        'articleGetter' => '\yadjet\mts\sdk\ArticleGetter',
                        'lookupGetter' => '\yadjet\mts\sdk\LookupGetter',
                    ],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => true,
            'rules' => [],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
