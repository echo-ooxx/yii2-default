<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2020/5/25
 * Time: 10:52 AM
 */

namespace backend\actions;


use yii\base\Action;
use Yii;
use yii\helpers\Url;

class BaseAction extends Action
{
    public $pageSize = 20;

    public $search;

    public $columns;

    public $title;

    public $highlight;

    public $view;

    public $scenario;

    public $modelClass;

    public $createUrl = 'create';

    public $subNav = null;

    public function setForward(){
        Yii::$app->session->set('__FORWARD__',$_SERVER['REQUEST_URI']);
    }

    public function getForward(){
        if(Yii::$app->session->has('__FORWARD__')){
            return Yii::$app->session->get('__FORWARD__');
        }else{
            return $this->redirect(['site/index']);
        }
    }

    public function redirect($url, $statusCode = 302){
        return Yii::$app->response->redirect(Url::to($url),$statusCode);
    }
}