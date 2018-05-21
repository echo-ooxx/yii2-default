<?php
/**
 * Created by PhpStorm.
 * User: leezhang
 * Date: 2017/11/28
 * Time: 下午10:20
 */

namespace common\helpers;

use yii;

class Weixin{

    private $appid;
    private $appsecret;

    public function __construct(){
        $this->appid = Yii::$app->params['wx_accout']['appid'];
        $this->appsecret = Yii::$app->params['wx_accout']['appsecret'];
    }

    public function getCode($redirect_uri){
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->appid.'&redirect_uri='.urlencode($redirect_uri).'&response_type=code&scope=snsapi_userinfo&state='.rand(0,9).'#wechat_redirect';
        Header("Location: $url");
        exit();
    }


    private function get($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }

}

?>