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
use Yii;

class CreateAction extends BaseAction
{
    public function run(){
        $this->controller->getView()->title = $this->title;

        $model = new $this->modelClass([
            'scenario' => $this->scenario
        ]);

        if(Yii::$app->request->isPost){
            $model->load(Yii::$app->request->getBodyParams());
            if($model->save()){
                Yii::$app->session->setFlash(Alert::TYPE_SUCCESS,'操作成功');
                return $this->redirect($this->getForward());
            }else{
                Yii::$app->session->setFlash(Alert::TYPE_ERROR,Tools::simpleerrors($model->errors));
            }
        }

        return $this->controller->render($this->view,[
            'model' => $model,
            'isNewRecord' => true,
            'highlight' => $this->highlight
        ]);
    }
}