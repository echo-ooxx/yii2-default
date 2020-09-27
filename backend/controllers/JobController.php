<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2020/9/22
 * Time: 4:28 PM
 */

namespace backend\controllers;


use backend\actions\DeleteAction;
use backend\actions\EditAction;
use backend\actions\ListAction;
use common\helpers\Html;
use common\modelsext\SiteJobExt;
use common\symbol\BaseSymbol;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;

class JobController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['list','create','update','delete'],
                        'allow' => true,
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'list' => [
                'class' => ListAction::class,
                'modelClass' => SiteJobExt::class,
                'title' => '职位列表',
                'highlight' => 'job/list',
                'columns' => [
                    [
                        'label' => '职位',
                        'value' => function($model){
                            $name = Json::decode($model->name,true);
                            return $name['cn'];
                        }
                    ],
                    [
                        'label' => '当前状态',
                        'value' => function ($model) {
                            return BaseSymbol::STATUS_MAP[$model->status];
                        }
                    ],
                    'created_at:datetime',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{update} {func-list} {delete}',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                $bts = Html::a('<span class="glyphicon glyphicon-pencil"></span> 编辑', $url, [
                                    'title' => '编辑',
                                    'class' => 'btn btn-default',
                                ]);
                                return $bts;
                            },
                            'delete' => function ($url, $model, $key) {
                                return Html::a('<span class="glyphicon glyphicon-trash"></span> 删除', $url, [
                                    'title' => '删除',
                                    'class' => 'btn btn-default',
                                    'data' => [
                                        'confirm' => '确定要删除么?',
                                        'method' => 'post',
                                    ],
                                ]);
                            }
                        ]
                    ]
                ]
            ],
            'create' => [
                'class' => EditAction::class,
                'modelClass' => SiteJobExt::class,
                'title' => '创建职位',
                'highlight' => 'job/list',
                'view' => '/job/edit',
                'scenario' => 'create'
            ],
            'update' => [
                'class' => EditAction::class,
                'modelClass' => SiteJobExt::class,
                'title' => '编辑职位',
                'highlight' => 'job/list',
                'view' => '/job/edit',
                'scenario' => 'update'
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => SiteJobExt::class,
                'title' => '删除职位',
                'highlight' => 'job/list',
                'scenario' => 'delete'
            ]
        ];
    }
}