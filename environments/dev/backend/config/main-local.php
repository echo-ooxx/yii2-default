<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators' => [
            'model' => [
                'class' => 'echoooxx\admin\generators\model\Generator',
                'templates' => [
                    'default' => '@echoooxx/admin/generators/model/default',
                ]
            ],
            'crud' => [
                'class' => 'echoooxx\admin\generators\crud\Generator',
                'templates' => [
                    'default' => '@echoooxx/admin/generators/crud/default',
                ]
            ],
            'controller' => [
                'class' => 'echoooxx\admin\generators\controller\Generator',
                'templates' => [
                    'default' => '@echoooxx/admin/generators/controller/default',
                ]
            ],
            'form' => [
                'class' => 'echoooxx\admin\generators\form\Generator',
                'templates' => [
                    'default' => '@echoooxx/admin/generators/form/default',
                ]
            ],
        ],
    ];
}

return $config;
