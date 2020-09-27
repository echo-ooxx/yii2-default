<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2020/9/22
 * Time: 4:41 PM
 */

namespace common\modelsext;


use common\helpers\ArrayHelper;
use common\models\SiteScroll;
use common\symbol\BaseSymbol;
use common\symbol\ScrollSymbol;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;

class SiteScrollExt extends SiteScroll
{
    public $name_cn;
    public $name_en;

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
                    'sort' => SORT_DESC,
                    'created_at' => SORT_DESC
                ]
            ],
        ]);

        return $dataProvider;
    }

    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(),[
            'name_cn',
            'name_en',
            'mobile',
            'pc'
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(),[
            'mobile' => '移动端图片',
            'pc' => '主机端图片',
            'name_cn' => '案例中文',
            'name_en' => '案例英文'
        ]);
    }

    public function rules()
    {
        return [
            [['mobile','pc','type'],'required'],
            [['sort','status'],'number']
        ];
    }

    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(),[
            'create' => array_keys($this->attributes),
            'update' => array_keys($this->attributes),
            'delete' => ['status']
        ]);
    }

    public function beforeValidate()
    {

        if($this->type == ScrollSymbol::TYPE_HOME){
            if(empty($this->name_cn)){
                $this->addError('name_cn','请填写案例中文');
            }
            if(empty($this->name_en)){
                $this->addError('name_en','请填写案例英文');
            }
            if(empty($this->category_text)){
                $this->addError('category_text','分类文案');
            }
        }

        return parent::beforeValidate(); // TODO: Change the autogenerated stub
    }

    public function afterValidate()
    {
        parent::afterValidate(); // TODO: Change the autogenerated stub
        if($this->name_cn && $this->name_en){
            $this->name = Json::encode([
                'cn' => $this->name_cn,
                'en' => $this->name_en
            ]);
        }
        $this->src = Json::encode([
            'pc' => $this->pc,
            'mobile' => $this->mobile
        ]);
    }

}