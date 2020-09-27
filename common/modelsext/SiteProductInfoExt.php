<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2020/9/25
 * Time: 4:15 PM
 */

namespace common\modelsext;

use common\helpers\ArrayHelper;
use common\models\SiteProductInfo;
use yii\data\ActiveDataProvider;

class SiteProductInfoExt extends SiteProductInfo
{
    public function search($params){
        $query = self::find();
        $this->load($params);
        $id = intval($params['id']);

        if($id > 0){
            $query
                ->andFilterWhere([
                    'product_id' => $id
                ]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20
            ],
            'sort' => [
                'defaultOrder' => [
                    'sort' => SORT_DESC
                ]
            ]
        ]);

        return $dataProvider;
    }

    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(),[
            'create' => array_keys($this->attributes),
            'update' => array_keys($this->attributes),
            'delete' => ['status']
        ]);
    }

    public function rules()
    {
        $rules = [
            [['title','content','product_id'],'required'],
            [['sort','product_id'],'number']
        ];
        return $rules;
    }
}