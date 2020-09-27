<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2020/9/23
 * Time: 4:29 PM
 */

namespace backend\controllers;


use backend\actions\DeleteAction;
use backend\actions\EditAction;
use backend\actions\ListAction;
use common\helpers\Html;
use common\modelsext\SiteAdditionalExt;
use common\symbol\AdditionalSymbol;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;

class OtherController extends BaseController
{

    public $title = '项目';
    public $modelClass = SiteAdditionalExt::class;
    public $highlight = 'other/list';

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
                'title' => '网站其他信息',
                'highlight' => $this->highlight,
                'columns' => [
                    [
                        'label' => '标题',
                        'value' => function($model){
                            return $model->title_text;
                        }
                    ],
                    [
                        'label' => '内容',
                        'format' => 'html',
                        'value' => function ($model) {
                            $type = $model->type;
                            $val = $model->value_text;
                            if($type == AdditionalSymbol::TYPE_IMG || $type == AdditionalSymbol::TYPE_IMGS){
                                if($type == AdditionalSymbol::TYPE_IMGS){
                                    $val = Json::decode($val);
                                    $val = $val['pc'];
                                }
                                return Html::img($val,[
                                    'height' => '100px'
                                ]);
                            }else{
                                return $val;
                            }
                        }
                    ],
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
                'modelClass' => $this->modelClass,
                'title' => '创建共用信息',
                'highlight' => $this->highlight,
                'view' => '/other/edit',
                'scenario' => 'create'
            ],
            'update' => [
                'class' => EditAction::class,
                'modelClass' => $this->modelClass,
                'title' => '编辑共用信息',
                'highlight' => $this->highlight,
                'view' => '/other/edit',
                'scenario' => 'update'
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => $this->modelClass,
                'title' => '删除共用信息',
                'highlight' => $this->highlight,
                'scenario' => 'delete'
            ]
        ];
    }
}