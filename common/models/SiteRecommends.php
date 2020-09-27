<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "site_recommends".
 *
 * @property int $id ID
 * @property string $cover 封面
 * @property string $link 链接
 * @property string $bg 背景颜色
 * @property int $status 当前状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class SiteRecommends extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_recommends';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['cover', 'link'], 'string', 'max' => 512],
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
            'cover' => '封面',
            'link' => '链接',
            'bg' => '背景颜色',
            'status' => '当前状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
