<?php
/**
 * Created by PhpStorm.
 * User: leezhang
 * Date: 2017/12/23
 * Time: 下午9:23
 */

namespace common\dog;

use yii\helpers\Json;
use yii;
use yii\web\BadRequestHttpException;

class Wxoath{

    public $appkey;
    public $appsecret;

    public function __construct($key,$secret){
        $this->appkey = $key;
        $this->appsecret = $secret;
    }

    public function callback_code($callback){
        $url_data['appid'] = $this->appkey;
        $url_data['redirect_uri'] = "$callback";
        $url_data["response_type"] = "code";
        $url_data["scope"] = "snsapi_userinfo";
        $url_data["state"] = rand(0,9)."#wechat_redirect";
        $str = $this->ToUrlParams($url_data);
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?".$str;
        header("Location:$url");
    }


    /**
     * @param $code
     * @return access_token,expires_in,openid
     */
    public function getOpenid($code){
        $url= 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->appkey.'&secret='.$this->appsecret.'&code='.$code.'&grant_type=authorization_code';
        $response = $this->httpRequest($url);
        if($response){
            $res = Json::decode($response,true);
            if(isset($res['errcode'])){
                //出错
                var_dump($res);
                exit();
            }else{
                $data['access_token'] = $res['access_token'];
                $data['expires_in'] = $res['expires_in'];
                $data['openid'] = $res['openid'];
                //根据openid存accesstoken
                //access_token存到cache里
                $openid_token_cache_key = $data['openid'].'_access_token_key';
                Yii::$app->cache->set($openid_token_cache_key,$data['access_token'],($data['expires_in'] - 200));
                return $data;
            }
        }
        return null;
    }

    /**
     * @param $openid
     * @param $accessToken
     * @return array
     * @throws BadRequestHttpException
     */
    public static function getUserByOpenid($openid,$accessToken){
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$accessToken.'&openid='.$openid.'&lang=zh_CN';
        $response = self::get($url);
        $info = Json::decode($response);
        if(is_array($info) && isset($info['openid'])){
            $data = [
                'openid' => $info['openid'],
                'nickname' => $info['nickname'],
                'sex' => $info['sex'],
                'city' => $info['city'],
                'country' => $info['country'],
                'province' => $info['province'],
                'headimgurl' => $info['headimgurl'],
                'unionid' => isset($info['openid'])?$info['openid']:'',
            ];
            return $data;
        }else{
            var_dump($info['errmsg']);
            exit();
        }
    }
    public static function get($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off'))
        {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        //curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.122 Safari/537.36');
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    protected function httpRequest($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off'))
        {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        //curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.122 Safari/537.36');
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    protected function ToUrlParams($urlObj)
    {
        $buff = "";
        foreach ($urlObj as $k => $v)
        {
            if($k != "sign"){
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }



}