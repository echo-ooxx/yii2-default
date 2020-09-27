<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "site_product".
 *
 * @property int $id ID
 * @property int $type_id 类别ID
 * @property string $cover 封面图
 * @property string $name 案例名称
 * @property string $bg 背景颜色
 * @property string $contents 详情
 * @property int $sort 排序
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property int $status 当前状态
 */
class SiteProduct extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type_id', 'sort', 'created_at', 'updated_at', 'status'], 'integer'],
            [['contents'], 'string'],
            [['cover', 'name'], 'string', 'max' => 512],
            [['bg'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => '类别ID',
            'cover' => '封面图',
            'name' => '案例名称',
            'bg' => '背景颜色',
            'contents' => '详情',
            'sort' => '排序',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'status' => '当前状态',
        ];
    }
}
