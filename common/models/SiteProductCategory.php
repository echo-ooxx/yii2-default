<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "site_product_category".
 *
 * @property int $id ID
 * @property string $icon 图标地址
 * @property string $src 图片
 * @property string $name 名称
 * @property int $sort 排序
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property int $status 当前状态
 */
class SiteProductCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_product_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sort', 'created_at', 'updated_at', 'status'], 'integer'],
            [['icon', 'src', 'name'], 'string', 'max' => 512],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'icon' => '图标地址',
            'src' => '图片',
            'name' => '名称',
            'sort' => '排序',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'status' => '当前状态',
        ];
    }
}
