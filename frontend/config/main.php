<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'forgo-app',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'api' => '\frontend\modules\api\Module'
    ],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning','info'],
                    'logFile' => '@frontend/logs/app.log',
                    'logVars' => [],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                    'categories' => ['yii\db\*'],
                    'logFile' => '@frontend/logs/db.log',
                    'logVars' => [],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'suffix' => '',
            'rules' => [
                '<controller:[\w._-]+>/<id:\d+>' => '<controller>/view',
                '<controller:[\w._-]+>/<action:[\w._-]+>' => '<controller>/<action>',
                '<controller:[\w._-]+>/<action:[\w._-]+>/<id:\d+>' => '<controller>/<action>',
                '<module:[\w._-]+>/<controller:[\w._-]+>/<action:[\w._-]+>' => '<module>/<controller>/<action>',
                '<module:[\w._-]+>/<controller:[\w._-]+>/<action:[\w._-]+>/<id:\d+>' => '<module>/<controller>/<action>',
            ],
        ],
    ],
    'params' => $params,
];
