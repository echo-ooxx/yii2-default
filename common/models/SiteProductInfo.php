<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "site_product_info".
 *
 * @property int $id ID
 * @property int $product_id 项目ID
 * @property string $title 项目信息标题
 * @property string $content 项目信息内容
 * @property int $sort 排序
 */
class SiteProductInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_product_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'sort'], 'integer'],
            [['title', 'content'], 'string', 'max' => 512],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => '项目ID',
            'title' => '项目信息标题',
            'content' => '项目信息内容',
            'sort' => '排序',
        ];
    }
}
