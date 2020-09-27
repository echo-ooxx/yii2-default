<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2020/9/26
 * Time: 10:32 AM
 */

namespace common\modelsext;


use common\helpers\ArrayHelper;
use common\models\SiteRecommends;
use common\symbol\BaseSymbol;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;

class SiteRecommendsExt extends SiteRecommends
{

    public $product_ids;

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
                    'created_at' => SORT_DESC
                ]
            ]
        ]);

        return $dataProvider;
    }

    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(),[
            'product_ids',
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(),[
            'product_ids' => '所属项目',
        ]);
    }

    public function rules()
    {
        $rule = parent::rules();

        return ArrayHelper::merge($rule,[
            ['product_ids','each','rule' => ['string']]
        ]);
    }

    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(),[
            'create' => array_keys($this->attributes),
            'update' => array_keys($this->attributes),
            'delete' => ['status'],
        ]);
    }

    public function beforeValidate()
    {
        return parent::beforeValidate(); // TODO: Change the autogenerated stub
    }

    public function afterValidate()
    {
        parent::afterValidate(); // TODO: Change the autogenerated stub
        if($this->scenario == 'create'){
            $this->status = BaseSymbol::STATUS_NORMAL;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
        // 更新via
        RecommendViaProductExt::edit($this->id,$this->product_ids);
    }

    public function getProducts(){
        return $this->hasMany(SiteProductExt::class,['id' => 'product_id'])->viaTable(RecommendViaProductExt::tableName(),['recommend_id' => 'id']);
    }

}