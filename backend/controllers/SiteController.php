<?php
namespace backend\controllers;

use common\dog\Tools;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error','uploadimage', 'ueditor', 'upload4kv'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {

        $isAddWater = intval(Yii::$app->request->get('isAddWater'));
        $uploadType = Tools::cleanXSS(Yii::$app->request->get('uploadType'));
        $uploadType = $uploadType ? $uploadType : 'base64';

        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            /* 单图、多图上传 */
            'uploadimage' => [
                'class' => 'common\widgets\images\UploadAction',
                'uploadTo' => 'qiniu',
                'uploadType' => $uploadType
            ],
            /* ueditor文件上传 */
            'ueditor' => [
                'class' => 'backend\actions\UEditorAction',
                'config' => Yii::$app->params['ueditorConfig'],
                'isAddWater' => $isAddWater,
                'uploadTo' => 'qiniu'
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        $this->layout = 'login.php';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
