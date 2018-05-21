<?php
/**
 * Created by PhpStorm.
 * User: leezhang
 * Date: 2017/11/28
 * Time: 下午9:42
 */

namespace common\helpers;

use yii;

class WxHelper{

    private $appid;
    private $appsecret;
    private $accessTokenKey = 'cache_wxbase_access_token';

    public function __construct(){
        $this->appid = Yii::$app->params['wx_accout']['appid'];
        $this->appsecret = Yii::$app->params['wx_accout']['appsecret'];
    }


    public function getAccessToken($refresh = false){

        if(!Yii::$app->cache->exists($this->accessTokenKey) || $refresh){

            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appid.'&secret='.$this->appsecret;
            $response = $this->get($url);
            $res = json_decode($response,true);

        }

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