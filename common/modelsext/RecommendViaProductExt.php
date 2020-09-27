<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2020/9/26
 * Time: 10:32 AM
 */

namespace common\modelsext;


use Yii;
use common\models\RecommendViaProduct;
use yii\db\Exception;

class RecommendViaProductExt extends RecommendViaProduct
{
    public static function edit($recommend_id,$product_ids){
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        $data = null;
        foreach ($product_ids as $key => $val){
            $data[] = [$recommend_id,$val];
        }
        try{
            // 删除现有结果
            self::deleteAll(['recommend_id' => $recommend_id]);
            // 添加新的关系
//            $sql = $db->createCommand()->batchInsert(self::tableName(),['recommend_id','product_id'],array_keys($product_ids))->getRawSql();
            $db->createCommand()->batchInsert(self::tableName(),['recommend_id','product_id'],$data)->execute();
            $transaction->commit();
        }catch (Exception $e){
            $transaction->rollBack();
        }
    }
}