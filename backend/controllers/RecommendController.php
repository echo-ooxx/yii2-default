<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2020/9/26
 * Time: 10:31 AM
 */

namespace backend\controllers;


use backend\actions\DeleteAction;
use backend\actions\EditAction;
use Yii;
use backend\actions\ListAction;
use common\helpers\Html;
use common\modelsext\SiteRecommendsExt;
use common\symbol\BaseSymbol;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class RecommendController extends BaseController
{
    public $title = '推荐管理';
    public $modelClass = SiteRecommendsExt::class;
    public $highlight = 'recommend/list';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['list','create','update','delete','content-edit'],
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
                        'label' => '图片',
                        'format' => 'html',
                        'value' => function($model){
                            $img = $model->cover;
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
                        'template' => '{update} {content-edit} {info-list} {delete} {preview}',
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
            ],
            'create' => [
                'class' => EditAction::class,
                'modelClass' => $this->modelClass,
                'title' => '创建' . $this->title,
                'highlight' => $this->highlight,
                'view' => '/recommend/edit',
                'scenario' => 'create'
            ],
            'update' => [
                'class' => EditAction::class,
                'modelClass' => $this->modelClass,
                'title' => '编辑' . $this->title,
                'highlight' => $this->highlight,
                'view' => '/recommend/edit',
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