<?php

namespace frontend\modules\api;
use echoooxx\yii2rest\ErrorHandler;
use yii\base\InvalidConfigException;
use Yii;

/**
 * api module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'frontend\modules\api\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        try {

            $request = Yii::$app->get('request');
            $request->parsers = [
                'application/json' => 'yii\web\JsonParser',
            ];
            Yii::$app->set('request', $request);

            Yii::$app->set('errorHandler', [
                'class' => ErrorHandler::class,
                'errorAction' => $this->getUniqueId() . '/default/error',
            ]);
            Yii::$app->get('errorHandler')->register();



        }catch (InvalidConfigException $e){
            Yii::error($e);
        }

        // custom initialization code goes here
    }
}
