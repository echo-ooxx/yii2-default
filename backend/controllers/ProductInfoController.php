<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2020/9/25
 * Time: 4:17 PM
 */

namespace backend\controllers;


use common\dog\Alert;
use common\dog\Tools;
use Yii;
use backend\actions\DeleteAction;
use backend\actions\EditAction;
use backend\actions\ListAction;
use common\helpers\Html;
use common\modelsext\SiteProductInfoExt;
use common\symbol\BaseSymbol;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class ProductInfoController extends BaseController
{
    public $title = '项目信息';
    public $modelClass = SiteProductInfoExt::class;
    public $highlight = 'product-info/list';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['list','create','update','delete','edit'],
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
                        'label' => $this->title . '标题',
                        'value' => function($model){
                            return $model->title;
                        }
                    ],
                    [
                        'label' => $this->title . '内容',
                        'value' => function($model){
                            return $model->content;
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{update} {delete}',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                $bts = Html::a('<span class="glyphicon glyphicon-pencil"></span> 编辑', $url . '&product_id=' . $model->product_id, [
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
                'title' => '创建' . $this->title,
                'highlight' => $this->highlight,
                'view' => '/product/info-edit',
                'scenario' => 'create'
            ],
            'update' => [
                'class' => EditAction::class,
                'modelClass' => $this->modelClass,
                'title' => '编辑' . $this->title,
                'highlight' => $this->highlight,
                'view' => '/product/info-edit',
                'scenario' => 'update'
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'delete' => true,
                'modelClass' => $this->modelClass,
                'title' => '删除' . $this->title,
                'highlight' => $this->highlight,
                'scenario' => 'delete'
            ]
        ];
    }

    public function actionEdit(){
        $product_id = intval(Yii::$app->request->get('product_id'));
        $model = new SiteProductInfoExt();
        $model->scenario = 'create';
        $post = Yii::$app->request->post();
        $post[$model->formName()]['product_id'] = $product_id;
        $model->load($post);
        if($model->save()){
            Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, '操作成功');
        }else{
            Yii::$app->session->setFlash(Alert::TYPE_ERROR, Tools::simpleerrors($model->errors));
        }
        return $this->redirect($this->getForward());
    }
}