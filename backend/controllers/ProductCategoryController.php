<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2020/9/22
 * Time: 2:31 PM
 */

namespace backend\controllers;


use backend\actions\DeleteAction;
use backend\actions\EditAction;
use backend\actions\ListAction;
use common\helpers\Html;
use common\modelsext\SiteProductCategoryExt;
use common\symbol\BaseSymbol;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;

class ProductCategoryController extends BaseController
{
    public $title = '项目分类';
    public $modelClass = SiteProductCategoryExt::class;
    public $highlight = 'product-category/list';


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
                'modelClass' => $this->modelClass,
                'title' => $this->title . '列表',
                'highlight' => $this->highlight,
                'columns' => [
                    [
                        'label' => '分类名',
                        'value' => function($model){
                            $name = Json::decode($model->name,true);
                            return $name['cn'];
                        }
                    ],
                    [
                        'label' => '当前状态',
                        'attribute' => 'status',
                        'value' => function ($model) {
                            return BaseSymbol::STATUS_MAP[$model->status];
                        }
                    ],
                    [
                        'label' => '排序',
                        'value' => function ($model) {
                            return $model->sort;
                        }
                    ],
                    'created_at:datetime',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{update} {content-edit} {info-add} {info-list} {delete} {preview}',
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
                            },
                        ]
                    ]
                ],
                'search' => [
                    'name' => [
                        'type' => 'string'
                    ]
                ],
            ],
            'create' => [
                'class' => EditAction::class,
                'modelClass' => $this->modelClass,
                'title' => '创建' . $this->title,
                'highlight' => $this->highlight,
                'view' => '/product-category/edit',
                'scenario' => 'create'
            ],
            'update' => [
                'class' => EditAction::class,
                'modelClass' => $this->modelClass,
                'title' => '编辑' . $this->title,
                'highlight' => $this->highlight,
                'view' => '/product-category/edit',
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