<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2020/9/23
 * Time: 9:35 AM
 */

namespace common\querys;


use common\modelsext\SiteScrollExt;
use common\symbol\BaseSymbol;
use common\symbol\ScrollSymbol;
use yii\data\ActiveDataProvider;

class ContactScrollQuery extends SiteScrollExt
{
    public function search($params){
        $query = self::find();
        $query
            ->andFilterWhere([
                'type' => ScrollSymbol::TYPE_CONTACT
            ]);
        $query
            ->andFilterWhere([
                'in','status',[BaseSymbol::STATUS_NORMAL,BaseSymbol::STATUS_DRAFT]
            ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20
            ],
            'sort' => [
                'defaultOrder' => [
                    'sort' => SORT_DESC,
                    'created_at' => SORT_DESC
                ]
            ],
        ]);

        return $dataProvider;
    }
}