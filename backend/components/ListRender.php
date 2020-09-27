<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2019/4/12
 * Time: 8:57 PM
 */

namespace backend\components;

use yii\base\BaseObject;
use yii\db\ActiveRecord;
use Yii;
use yii\data\Pagination;

class ListRender extends BaseObject
{
    private $pageSize = 20;
    private $model;
    private $layouts;
    private $lists;
    private $pagination;

    public function setPagesize($pagesize){
        $this->pageSize = $pagesize;
    }

    public function setModel(ActiveRecord $model){
        $this->model = $model;
    }

    public function setLayouts($layouts){
        $this->layouts = $layouts;
    }

    public function listing($where,$orderby,$with = null){
        $query = $this->model::find();
        $_conditions = [];
        foreach ($where as $key => $value){
            if($key !== 'like'){
                $_conditions[$key] = $value;
            }
        }
        $query
            ->where($_conditions);
        if(array_key_exists('like',$where)){
            $query
                ->andWhere([
                    'like',$where['like']['label'],$where['like']['text']
                ]);
        }
        $count = $query->count();
        $this->pagination = new Pagination(['totalCount' => $count,'pageSize' => $this->pageSize]);
        if($with){
            $query
                ->with($with);
        }
        $this->lists = $query
            ->offset($this->pagination->offset)
            ->limit($this->pagination->limit)
            ->orderBy($orderby)
            ->asArray()
            ->all();
    }

    public function rendering($params = null){
        $_params = [
            'lists' => $this->lists,
            'pagination' => $this->pagination
        ];

        $params = $params ? array_merge($_params,$params) : $_params;
        $content = Yii::$app->getView()->render($this->layouts,$params,Yii::$app->controller);
        return Yii::$app->controller->renderContent($content);
    }

}