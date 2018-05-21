<?php
/**
 * Created by PhpStorm.
 * User: leezhang
 * Date: 2017/11/21
 * Time: 下午4:24
 */

namespace common\dog;

use Yii;
use yii\bootstrap\Widget;
use yii\helpers\Html;

class Alert extends Widget{
    const TYPE_ERROR="error";
    const TYPE_DANGER="danger";
    const TYPE_SUCCESS="success";
    const TYPE_INFO="info";
    const TYPE_WARNING="warning";

    private $alertTypes = [
        'error' => [
            'class' => 'alert-danger',
            'icon' => '<i class="icon fa fa-ban"></i>',
        ],
        'danger' => [
            'class' => 'alert-danger',
            'icon' => '<i class="icon fa fa-ban"></i>',
        ],
        'success' => [
            'class' => 'alert-success',
            'icon' => '<i class="icon fa fa-check"></i>',
        ],
        'info' => [
            'class' => 'alert-info',
            'icon' => '<i class="icon fa fa-info"></i>',
        ],
        'warning' => [
            'class' => 'alert-warning',
            'icon' => '<i class="icon fa fa-warning"></i>',
        ],
    ];

    private $type_msg = [
        self::TYPE_ERROR => '错误',
        self::TYPE_DANGER => '危险',
        self::TYPE_SUCCESS => '成功',
        self::TYPE_INFO => '信息',
        self::TYPE_WARNING => '注意'
    ];

    public function run() {
        $session = Yii::$app->getSession();
        if($session)
        {
            $html="";
            $flashes = $session->getAllFlashes();
            if(!empty($flashes)){
                foreach ($flashes as $type => $data) {
                    if(isset($this->alertTypes[$type])){
                        if(is_array($data))
                            $data=nl2br(Html::encode(implode("\n",$data)));
                        $html= Html::beginTag('div',['class'=>'alert alert-dismissable '.$this->alertTypes[$type]['class']]);
                        $html .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
                        $html .= Html::tag('h4',$this->alertTypes[$type]['icon'].$this->type_msg[$type]);
                        $html .= $data;
                        $html .= Html::endTag('div');
                        $session->removeFlash($type);
                    }
                }
            }
            return $html;
        }
        return "";
    }

}
?>

