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
use yii\web\NotFoundHttpException;

class EditAction extends BaseAction
{
    public function run($id = 0)
    {
        $this->controller->getView()->title = $this->title;

        $id = intval($id);

        $model = new $this->modelClass();

        if ($id > 0) {
            $model = $model::findOne($id);
            if (!$model) {
                throw new NotFoundHttpException('没有数据');
            }
        }

        $model->scenario = $this->scenario;

        $isNewRecord = $model->isNewRecord;

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->getBodyParams());
            if ($model->save()) {
                Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, '操作成功');
                if ($isNewRecord) {
                    return $this->redirect($this->getForward());
                }
            } else {
                Yii::$app->session->setFlash(Alert::TYPE_ERROR, Tools::simpleerrors($model->errors));
            }
        }

        return $this->controller->render('/site/edit', [
            'subView' => $this->view,
            'model' => $model,
            'isNewRecord' => $isNewRecord,
            'highlight' => $this->highlight
        ]);
    }
}