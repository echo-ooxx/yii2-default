<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "recommend_via_product".
 *
 * @property int $id ID
 * @property int $recommend_id 推荐ID
 * @property int $product_id 项目ID
 */
class RecommendViaProduct extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'recommend_via_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['recommend_id', 'product_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'recommend_id' => '推荐ID',
            'product_id' => '项目ID',
        ];
    }
}
