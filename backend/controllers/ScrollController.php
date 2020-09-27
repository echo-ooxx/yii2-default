<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2020/9/22
 * Time: 4:32 PM
 */

namespace backend\controllers;


use backend\actions\DeleteAction;
use backend\actions\EditAction;
use backend\actions\ListAction;
use common\helpers\Html;
use common\modelsext\SiteScrollExt;
use common\querys\ContactScrollQuery;
use common\querys\HomeScrollQuery;
use common\symbol\BaseSymbol;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;

class ScrollController extends BaseController
{
    public $title = '首页轮播';
    public $modelClass = SiteScrollExt::class;
    public $highlight = 'scroll/list';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['home-list','contact-list','list','create','update','delete'],
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
            'home-list' => [
                'class' => ListAction::class,
                'modelClass' => HomeScrollQuery::class,
                'title' => '首页轮播列表',
                'highlight' => 'scroll/home-list',
                'columns' => [
                    [
                        'label' => '图片',
                        'format' => 'html',
                        'value' => function($model){
                            $img = Json::decode($model->src);
                            $img = $img['pc'];
                            return Html::img($img,[
                                'height' => '100px'
                            ]);
                        }
                    ],
                    [
                        'label' => '分类文案',
                        'attribute' => 'category_text',
                    ],
                    [
                        'label' => '当前状态',
                        'attribute' => 'status',
                        'value' => function ($model) {
                            return BaseSymbol::STATUS_MAP[$model->status];
                        }
                    ],
                    'created_at:datetime',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{update} {content-edit} {info-add} {delete}',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                $bts = Html::a('<span class="glyphicon glyphicon-pencil"></span> 编辑', $url, [
                                    'title' => '编辑',
                                    'class' => 'btn btn-primary',
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
            'contact-list' => [
                'class' => ListAction::class,
                'modelClass' => ContactScrollQuery::class,
                'title' => '联系轮播列表',
                'highlight' => 'scroll/contact-list',
                'columns' => [
                    [
                        'label' => '图片',
                        'format' => 'html',
                        'value' => function($model){
                            $img = Json::decode($model->src);
                            $img = $img['pc'];
                            return Html::img($img,[
                                'height' => '100px'
                            ]);
                        }
                    ],
                    [
                        'label' => '当前状态',
                        'attribute' => 'status',
                        'value' => function ($model) {
                            return BaseSymbol::STATUS_MAP[$model->status];
                        }
                    ],
                    'created_at:datetime',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{update} {content-edit} {info-add} {delete}',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                $bts = Html::a('<span class="glyphicon glyphicon-pencil"></span> 编辑', $url, [
                                    'title' => '编辑',
                                    'class' => 'btn btn-primary',
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
            'list' => [
                'class' => ListAction::class,
                'modelClass' => $this->modelClass,
                'title' => $this->title . '列表',
                'highlight' => $this->highlight,
                'columns' => [
                    [
                        'label' => '轮播图标题',
                        'value' => function($model){
                            return $model->title;
                        }
                    ],
                    [
                        'label' => '图片',
                        'format' => 'html',
                        'value' => function($model){
                            $img = Json::decode($model->img);
                            $img = $model->type === 1 ? $img['pc'] : $img['mobile'];
                            return Html::img($img,[
                                'width' => '100px'
                            ]);
                        }
                    ],
                    [
                        'label' => '所属分类',
                        'attribute' => 'type',
                        'value' => function ($model) {
                            return $model->type === 0 ? '未分类' : ($model->type === 1 ? '主机端' : '移动端');
                        }
                    ],
                    [
                        'label' => '当前状态',
                        'attribute' => 'status',
                        'value' => function ($model) {
                            return BaseSymbol::STATUS_MAP[$model->status];
                        }
                    ],
                    'created_at:datetime',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{update} {content-edit} {info-add} {delete}',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                $bts = Html::a('<span class="glyphicon glyphicon-pencil"></span> 编辑', $url, [
                                    'title' => '编辑',
                                    'class' => 'btn btn-primary',
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
                'modelClass' => $this->modelClass,
                'title' => '创建轮播',
                'highlight' => $this->highlight,
                'view' => '/scroll/edit',
                'scenario' => 'create'
            ],
            'update' => [
                'class' => EditAction::class,
                'modelClass' => $this->modelClass,
                'title' => '编辑' . $this->title,
                'highlight' => $this->highlight,
                'view' => '/scroll/edit',
                'scenario' => 'update'
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => $this->modelClass,
                'title' => '删除' . $this->title,
                'highlight' => $this->highlight,
                'scenario' => 'delete'
            ]
        ];
    }
}