<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2020/9/23
 * Time: 4:32 PM
 */

namespace common\modelsext;


use Yii;
use common\helpers\ArrayHelper;
use common\models\SiteAdditional;
use common\symbol\BaseSymbol;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;

class SiteAdditionalExt extends SiteAdditional
{

    public $mobile;
    public $pc;

    public function behaviors()
    {
        return [
            'class' => TimestampBehavior::class
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
            ],
        ]);

        return $dataProvider;
    }

    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(),[
            'mobile',
            'pc'
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(),[
            'pc' => '主机端图片',
            'mobile' => '移动端图片',
        ]);
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),[
            [['mobile','pc'],'string']
        ]);
    }

    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(),[
            'create' => array_keys($this->attributes),
            'update' => array_keys($this->attributes),
            'delete' => ['status']
        ]);
    }

    public function afterValidate()
    {
        parent::afterValidate(); // TODO: Change the autogenerated stub
        if($this->pc && $this->mobile){
            $this->value_text = Json::encode([
                'pc' => $this->pc,
                'mobile' => $this->mobile
            ]);
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
        self::storeCache(true);
    }

    public static function storeCache($refresh = false){
        $key = 'site:additional:info';
        if(!Yii::$app->cache->exists($key) || $refresh){
            $lists = self::find()
                        ->where([
                            'status' => BaseSymbol::STATUS_NORMAL
                        ])
                        ->asArray()
                        ->all();
            if($lists){
                Yii::$app->cache->set($key,$lists);
            }else{
                return null;
            }
        }else{
            return Yii::$app->cache->get($key);
        }
    }
}