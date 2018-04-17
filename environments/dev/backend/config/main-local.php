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
                'class' => 'echoxxoo\admin\generators\model\Generator',
                'templates' => [
                    'default' => '@echoxxoo/admin/generators/model/default',
                ]
            ],
            'crud' => [
                'class' => 'echoxxoo\admin\generators\crud\Generator',
                'templates' => [
                    'default' => '@echoxxoo/admin/generators/crud/default',
                ]
            ],
            'controller' => [
                'class' => 'echoxxoo\admin\generators\controller\Generator',
                'templates' => [
                    'default' => '@echoxxoo/admin/generators/controller/default',
                ]
            ],
            'form' => [
                'class' => 'echoxxoo\admin\generators\form\Generator',
                'templates' => [
                    'default' => '@echoxxoo/admin/generators/form/default',
                ]
            ],
        ],
    ];
}

return $config;
