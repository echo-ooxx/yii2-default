<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "site_scroll".
 *
 * @property int $id ID
 * @property string $name 案例名称
 * @property string $category_text 类别文字
 * @property string $src 图片地址
 * @property string $link 链接
 * @property int $type 所属类别
 * @property int $sort 排序
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property int $status 当前状态
 */
class SiteScroll extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_scroll';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'sort', 'created_at', 'updated_at', 'status'], 'integer'],
            [['name', 'src', 'link'], 'string', 'max' => 512],
            [['category_text'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '案例名称',
            'category_text' => '类别文字',
            'src' => '图片地址',
            'link' => '链接',
            'type' => '所属类别',
            'sort' => '排序',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'status' => '当前状态',
        ];
    }
}
