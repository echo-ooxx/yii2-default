<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2020/9/27
 * Time: 9:43 AM
 */

namespace frontend\modules\api\controllers;


use common\modelsext\SiteJobExt;
use common\symbol\BaseSymbol;
use yii\helpers\Json;

class JobController extends DefaultController
{
    public function actionList(){
        $query = SiteJobExt::find();
        $query
            ->where([
                'status' => BaseSymbol::STATUS_NORMAL
            ]);
        $lists = $query
                    ->orderBy('sort desc,created_at desc')
                    ->asArray()
                    ->all();
        $temp = null;
        if($lists){
            foreach ($lists as $key => $value){
                $_temp = $value;
                $_temp['name'] = Json::decode($value['name']);
                $temp[] = $_temp;
            }
        }
        return $this->success([
            'lists' => $temp
        ]);
    }
}