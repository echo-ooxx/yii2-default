<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2020/9/25
 * Time: 2:50 PM
 */

namespace frontend\modules\api\controllers;

use common\modelsext\SiteProductCategoryExt;
use common\modelsext\SiteProductExt;
use common\symbol\BaseSymbol;
use Yii;
use yii\db\Expression;
use yii\helpers\Json;


class ProductController extends DefaultController
{

    public function actionList(){
        $type = intval(Yii::$app->request->get('type'));
        $query = SiteProductExt::find();
        $query
            ->where([
                'status' => BaseSymbol::STATUS_NORMAL,
                'type_id' => $type
            ]);
        $lists = $query
                    ->orderBy('sort desc,created_at desc')
                    ->asArray()
                    ->all();
        $temp = null;
        if($lists){
            foreach ($lists as $key => $val){
                $_temp = $val;
                $_temp['name'] = Json::decode($val['name']);
                $temp[] = $_temp;
            }
        }
        return $this->success([
            'lists' => $temp
        ]);
    }

    public function actionDetail(){
        $id = intval(Yii::$app->request->get('id'));
        if($id == 0){
            return $this->fail(500,'参数错误');
        }
        $model = SiteProductExt::findOne($id);
        if(!$model){
            return $this->fail(404,'没有案例');
        }
        $model->contents = Json::decode($model->contents);
        $model->name = Json::decode($model->name);
        $nextWhere = new Expression('(type_id = ' . $model->type_id . ' and status = ' . BaseSymbol::STATUS_NORMAL . ') and ((sort < ' . $model->sort . ') or ((sort = ' . $model->sort . ') and (created_at < ' . $model->created_at . ')))');
        $next = SiteProductExt::find()
            ->where($nextWhere)
            ->orderBy('sort desc,created_at desc')
            ->asArray()
            ->one();
        $prevWhere = new Expression('(type_id = ' . $model->type_id . ' and status = ' . BaseSymbol::STATUS_NORMAL . ') and ((sort > ' . $model->sort . ') or ((sort = ' . $model->sort . ') and (created_at  > ' . $model->created_at .')))');
        $prev = SiteProductExt::find()
            ->where($prevWhere)
            ->orderBy('sort asc,created_at asc')
            ->asArray()
            ->one();
        return $this->success([
            'project' => $model,
            'info' => $model->info,
            'next' => $next ? $next : null,
            'prev' => $prev ? $prev : null,
            'recommend' => $model->recommends ? $model->recommends : null
        ]);
    }

    public function actionTypes(){
        $types = null;
        $temp = SiteProductCategoryExt::getTypes();
        if($temp){
            foreach ($temp as $key => $value) {
                $_temp = $value;
                $name = Json::decode($_temp['name']);
                $icons =Json::decode($_temp['icon']);
                $_temp['name'] = $name;
                $_temp['icon'] = $icons;
                $types[] = $_temp;
            }
        }
        return $this->success([
            'lists' => $types
        ]);
    }
}