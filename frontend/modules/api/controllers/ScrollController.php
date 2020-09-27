<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2020/9/27
 * Time: 9:55 AM
 */

namespace frontend\modules\api\controllers;


use common\modelsext\SiteScrollExt;
use common\symbol\BaseSymbol;
use common\symbol\ScrollSymbol;
use yii\helpers\Json;

class ScrollController extends DefaultController
{
    public function actionHome(){
        return $this->getList('home');
    }

    public function actionContact(){
        return $this->getList('contact');
    }

    public function getList($type){
        $type = $type === 'home' ? ScrollSymbol::TYPE_HOME : ScrollSymbol::TYPE_CONTACT;
        $query = SiteScrollExt::find();
        $query
            ->where([
                'status' => BaseSymbol::STATUS_NORMAL,
                'type' => $type
            ]);
        $lists = $query
                    ->orderBy('sort desc,created_at desc')
                    ->asArray()
                    ->all();
        $temp = null;
        if($lists){
            foreach ($lists as $key => $value){
                $_temp = $value;
                $_temp['src'] = Json::decode($value['src']);
                $_temp['name'] = $value['name'] ? Json::decode($value['name']) : '';
                $temp[] = $_temp;
            }
        }
        return $this->success([
            'lists' => $temp
        ]);
    }
}