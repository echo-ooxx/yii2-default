<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "site_job".
 *
 * @property int $id ID
 * @property string $name 职位名称
 * @property string $content 详情内容
 * @property int $sort 排序
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property int $status 当前状态
 */
class SiteJob extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_job';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['sort', 'created_at', 'updated_at', 'status'], 'integer'],
            [['name'], 'string', 'max' => 512],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '职位名称',
            'content' => '详情内容',
            'sort' => '排序',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'status' => '当前状态',
        ];
    }
}
