<?php
/**
 * Created by PhpStorm.
 * User: leezhang
 * Date: 2018/5/21
 * Time: 上午10:33
 */

namespace backend\controllers;


use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii;
use yii\helpers\Json;
use yii\helpers\Url;

class BaseController extends Controller
{
    public $admin;

    public $pageSize = 20;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function setForward(){
        Yii::$app->session->set('__FORWARD__',$_SERVER['REQUEST_URI']);
    }

    public function getForward(){
        if(Yii::$app->session->has('__FORWARD__')){
            return Yii::$app->session->get('__FORWARD__');
        }else{
            return Url::toRoute(['site/index']);
        }
    }

    public function formatData($data,$getKey = false){
        $arr = [];
        if(is_array($data) && $getKey){
            foreach ($data as $key => $value){
                $arr[$value['id']] = $value[$getKey];
            }
        }
        return $arr;
    }

    public function ajax_success($data = null)
    {
        $re = [
            'status' => '0',
            'error' => '',
            'data' => $data,
        ];
        echo Json::encode($re);
        Yii::$app->end();
    }

    public function ajax_fail($code, $error, $data = null)
    {
        $re = [
            'status' => $code,
            'error' => $error,
            'data' => $data,
        ];
        echo Json::encode($re);
        Yii::$app->end();
    }
}