<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "site_additional".
 *
 * @property int $id ID
 * @property string $key_text 键值
 * @property string $title_text 标题名称
 * @property string $value_text 具体内容
 * @property int $type 值类别
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property int $status 当前状态
 */
class SiteAdditional extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_additional';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'created_at', 'updated_at', 'status'], 'integer'],
            [['key_text', 'title_text', 'value_text'], 'string', 'max' => 512],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'key_text' => '键值',
            'title_text' => '标题名称',
            'value_text' => '具体内容',
            'type' => '值类别',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'status' => '当前状态',
        ];
    }
}
