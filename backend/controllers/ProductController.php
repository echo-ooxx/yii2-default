<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2020/9/22
 * Time: 2:13 PM
 */

namespace backend\controllers;


use common\dog\Tools;
use Yii;
use backend\actions\DeleteAction;
use backend\actions\EditAction;
use backend\actions\ListAction;
use common\helpers\Html;
use common\modelsext\SiteProductCategoryExt;
use common\modelsext\SiteProductExt;
use common\symbol\BaseSymbol;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;

class ProductController extends BaseController
{
    public $title = '项目';
    public $modelClass = SiteProductExt::class;
    public $highlight = 'product/list';

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
                        'label' => '项目名称',
                        'value' => function($model){
                            $name = Json::decode($model->name,true);
                            return $name['cn'];
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => '项目类别',
                        'template' => '{search}',
                        'buttons' => [
                            'search' => function($url,$model,$key){
                                $types = SiteProductCategoryExt::getTypes();
                                $types = array_filter($types,function($val) use ($model) {
                                    return $model->type_id == $val['id'];
                                });
                                $name = Json::decode(array_values($types)['0']['name']);
                                $condition['SiteProductsExt'] = [
                                    'type_id' => $model->type_id
                                ];
                                return Html::a($name['cn'],['',$model->formName() => [
                                    'type_id' => $model->type_id
                                ]]);
                            }
                        ]
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
                        'template' => '{update} {content-edit} {info-list} {delete} {preview}',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                $bts = Html::a('<span class="glyphicon glyphicon-pencil"></span> 编辑', $url, [
                                    'title' => '编辑',
                                    'class' => 'btn btn-primary',
                                ]);
                                return $bts;
                            },
                            'content-edit' => function($url,$model,$key){
                                $bts = Html::a('<span class="glyphicon glyphicon-pencil"></span> 详情编辑', $url, [
                                    'title' => '编辑',
                                    'class' => 'btn btn-primary',
                                ]);
                                return $bts;
                            },
                            'info-list' => function($url,$model,$key){
                                $url = Yii::$app->urlManager->createUrl(['product-info/list','id' => $model->id]);
                                $bts = Html::a('<span class="glyphicon glyphicon-pencil"></span> 项目信息列表', $url, [
                                    'title' => '列表',
                                    'class' => 'btn btn-success',
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
                'view' => '/product/edit',
                'scenario' => 'create'
            ],
            'update' => [
                'class' => EditAction::class,
                'modelClass' => $this->modelClass,
                'title' => '编辑' . $this->title,
                'highlight' => $this->highlight,
                'view' => '/product/edit',
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

    public function actionContentEdit(){
        $id = intval(Yii::$app->request->get('id'));
        if($id == 0){
            throw new BadRequestHttpException('请求错误');
        }
        $model = SiteProductExt::findOne($id);
        $isNewRecord = $model->isNewRecord;
        $highlight = 'product/list';
        $model->scenario = 'main-info';
        if(Yii::$app->request->isAjax){
            $_post = Yii::$app->request->post();
            $workContent = $_post['work_content'];
            if($workContent){
                $model->contents = Json::encode($workContent);
                $model->status = $_post['status'];
                if($model->save()){
                    return $this->ajax_success([
                        'msg' => 'OK',
                        'backUrl' => $this->getForward()
                    ]);
                }else{
                    return $this->ajax_fail(500,nl2br(implode('\n',Tools::simpleerrors($model->errors))));
                }
            }else{
                return $this->ajax_fail(500,'请上传内容');
            }
        }

        return $this->render('../multi-textarea/multi-textarea',[
            'model' => $model,
            'isNewRecord' => $isNewRecord,
            'highlight' => $highlight,
            'postUrl' => Yii::$app->urlManager->createUrl(['product/content-edit','id' => $id]),
            'statusNormal' => BaseSymbol::STATUS_NORMAL,
            'statusDraft' => BaseSymbol::STATUS_DRAFT,
            'works_content' => $model->contents ? Json::decode($model->contents) : null,
            'isScroll' => false,
            'backurl' => $this->getForward()
        ]);
    }

}