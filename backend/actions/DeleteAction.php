<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2020/5/25
 * Time: 10:53 AM
 */

namespace backend\actions;

use common\dog\Alert;
use common\dog\Tools;
use common\modelsext\SiteProductInfoExt;
use common\symbol\BaseSymbol;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use Yii;

class DeleteAction extends BaseAction
{
    public $modelClass;
    public $delete = false;

    public function run($id = 0){
        if($id === 0){
            throw new BadRequestHttpException('参数错误');
        }
        $model = $this->modelClass::findOne($id);
        if(!$model){
            throw new NotFoundHttpException('没有该数据');
        }
        if($this->delete){
            $model->delete();
        }else{
            $model->scenario = $this->scenario;
            $model->status = BaseSymbol::STATUS_DELETE;
            if($model->save()){
                Yii::$app->session->setFlash(Alert::TYPE_SUCCESS,'操作成功');
            }else{
                Yii::$app->session->setFlash(Alert::TYPE_ERROR,Tools::simpleerrors($model->errors));
            }
        }

        return $this->redirect($this->getForward());
    }
}