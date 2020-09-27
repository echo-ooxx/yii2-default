<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2020/5/25
 * Time: 10:53 AM
 */

namespace backend\actions;

use Yii;

class ListAction extends BaseAction
{
    public $view = '/site/list';

    public function run(){
        $this->setForward();
        $this->controller->getView()->title = $this->title;

        $model = new $this->modelClass;

        $requestParams = Yii::$app->request->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->request->getQueryParams();
        }
        $query = $model->search($requestParams);

        return $this->controller->render($this->view,[
            'dataProvider' => $query,
            'highlight' => $this->highlight,
            'columns' => $this->columns,
            'modelClass' => $model,
            'search' => $this->search,
            'createRoute' => $this->createUrl,
            'subNav' => $this->subNav
        ]);
    }
}