<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2020/9/22
 * Time: 2:24 PM
 */

namespace common\modelsext;

use common\helpers\ArrayHelper;
use common\models\SiteProduct;
use common\models\SiteProductInfo;
use common\symbol\BaseSymbol;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;

class SiteProductExt extends SiteProduct
{

    public $name_cn;
    public $name_en;

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class
            ]
        ];
    }

    public function search($params){
        $query = self::find();
        $this->load($params);

        if($this->type_id){
            $query
                ->andFilterWhere([
                    'type_id' => $this->type_id
                ]);
        }

        if($this->name){
            $query
                ->andFilterWhere([
                    'like','name',$this->name
                ]);
        }

        $query->andFilterWhere([
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
            ]
        ]);

        return $dataProvider;

    }

    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(),[
            'name_cn',
            'name_en',
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(),[
            'name_cn' => '中文名',
            'name_en' => '英文名',
        ]);
    }

    public function rules()
    {
        return [
            [['name_cn','name_en','cover','type_id'],'required'],
            [['sort','status'],'number'],
            ['name','string']
        ];
    }

    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(),[
            'create' => array_keys($this->attributes),
            'update' => array_keys($this->attributes),
            'delete' => ['status'],
            'main-info' => ['content','status']
        ]);
    }

    public function afterValidate()
    {
        parent::afterValidate(); // TODO: Change the autogenerated stub
        if($this->scenario == 'create' || $this->scenario == 'update'){
            $this->name = Json::encode([
                'cn' => $this->name_cn,
                'en' => $this->name_en
            ]);
        }

    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    public function getInfo(){
        return $this->hasMany(SiteProductInfoExt::class,['product_id' => 'id']);
    }

    public function getRecommends(){
        return $this->hasMany(SiteRecommendsExt::class,['id' => 'recommend_id'])->viaTable(RecommendViaProductExt::tableName(),['product_id' => 'id']);
    }

}