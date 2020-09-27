<?php

namespace frontend\modules\api\controllers;

use common\modelsext\SiteAdditionalExt;
use common\symbol\AdditionalSymbol;
use echoooxx\yii2rest\BaseController;
use Yii;
use yii\helpers\Json;
use yii\web\HttpException;

/**
 * Default controller for the `api` module
 */
class DefaultController extends BaseController
{

    public $enableBearerAuth = true;
    public $optional = ['*'];
    public $pageSize = 6;

    public function actionError()
    {
        if (null === ($exception = Yii::$app->getErrorHandler()->exception)) {
            $exception = new HttpException(404, Yii::t('yii', 'Page not found.'));
        }

        if ($exception instanceof HttpException) {
            $code = $exception->statusCode;
            $message = $exception->getMessage();
        } else {
            $code = 500;
            $message = $exception->getMessage();
        }

        return $this->fail($code, $message);
    }

    public function actionInfo(){
        $info = SiteAdditionalExt::storeCache();
        $temp = null;
        if($info){
            foreach ($info as $key => $value){
                $_temp = $value;
                if($value['type'] == AdditionalSymbol::TYPE_IMGS){
                    $_temp['value_text'] = Json::decode($value['value_text']);
                }
                $temp[] = $_temp;
            }
        }
        return $this->success([
            'info' => $temp
        ]);
    }
}
