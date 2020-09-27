<?php
/**
 * Created by PhpStorm.
 * User: leezhang
 * Date: 2017/11/22
 * Time: 下午9:04
 */

namespace common\dog;

use yii;
use common\helpers\Html;

class WxHelper{
    private $appkey;
    private $appsecret;
    private $key_jsapi;
    private $key_access;

    private $sign;

    public function __construct($appkey,$appsecret){
        $this->appkey = $appkey;
        $this->appsecret = $appsecret;
        $this->key_jsapi = 'jsapi_ticket_' . $appkey;
        $this->key_access = 'access_ticket_' .$appkey;
    }


    public function share($title = '',$description = '',$img_url = '',$url = ''){
        $csrf_header = \yii\web\Request::CSRF_HEADER;
        $csrf_token = Yii::$app->getRequest()->getCsrfToken();
        $this->sign = $this->doSign();
        if(empty($this->sign)){
            return '';
        }
        $title = Html::encode(str_replace(array("\r", "\n", "\t"), "", strip_tags($title)));
        $description = Html::encode(str_replace(array("\r", "\n", "\t"), "", strip_tags($description)));
        return <<<EOF
<script>
	wx.config({
		debug: false,
		appId: '{$this->sign["appId"]}',
		timestamp: '{$this->sign["timestamp"]}',
		nonceStr: '{$this->sign["nonceStr"]}',
		signature: '{$this->sign["signature"]}',
		jsApiList: [
			'onMenuShareTimeline',
			'onMenuShareAppMessage',
			'getLocation'
		]
	});
	wx.ready(function () {
		wx.onMenuShareAppMessage({
			title: '{$title}',
			desc: '{$description}',
			link: '{$url}',
			imgUrl: '{$img_url}',
			trigger: function (res) {

            },
            success: function (res) {
                share(res);
            },
            cancel: function (res) {

            },
            fail: function (res) {
                alert(JSON.stringify(res));
            }
		});
		wx.onMenuShareTimeline({
			title: '{$title}',
			link: '{$url}',
			imgUrl: '{$img_url}',
			trigger: function (res) {

            },
            success: function (res) {
                
            },
            cancel: function (res) {

            },
            fail: function (res) {
                alert(JSON.stringify(res));
            }
		});
	});

</script>
EOF;
    }


    public function doSign(){
        $ticket = $this->getJsApiTicket();
        if(empty($ticket)){
            return [];
        }
        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        $timestamp = time();
        $nonceStr = $this->createNonceStr();
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$ticket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string);
        $signPackage = array(
            "appId" => $this->appkey,
            "nonceStr" => $nonceStr,
            "timestamp" => $timestamp,
            "url" => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++)
        {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }

        return $str;
    }

    public function getJsApiTicket(){
        if(!Yii::$app->cache->exists($this->key_jsapi)){
            $accessToken = $this->getAccessToken();
            if($accessToken){
                // 如果是企业号用以下 URL 获取 ticket
                // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
                $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
                $response = $this->httpGet($url);
                $res = json_decode($response,true);
                if(!empty($res)){
                    if(isset($res['errcode']) && $res['errcode'] > 0){
                        Yii::warning('微信请求weixin_jsticket出错');
                        Yii::warning(json_encode($res));
                        return "";
                    }else{
                        Yii::$app->cache->set($this->key_jsapi,$res['ticket'],$res['expires_in'] - 200);
                    }
                }else{
                    Yii::warning('微信请求weixin_jsticket出错');
                    Yii::warning($response);
                    return "";
                }
            }
        }
        return Yii::$app->cache->get($this->key_jsapi);
    }

    public function getAccessToken($refresh = false){
        if(!Yii::$app->cache->exists($this->key_access) || $refresh){
            // 如果是企业号用以下URL获取access_token
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appkey&corpsecret=$this->appsecret";
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appkey&secret=$this->appsecret";
            $response = $this->httpGet($url);
            $res = json_decode($response,true);
            if(!empty($res)){
                if(isset($res['errcode']) && $res['errcode'] > 0){
                    Yii::warning('微信请求weixin_accesstoken出错');
                    Yii::warning(json_encode($res));
                    return "";
                }else{
                    Yii::$app->cache->set($this->key_access,$res['access_token'],$res['expires_in'] - 200);
                }
            }else{
                Yii::warning('微信请求weixin_accesstoken出错');
                Yii::warning($response);
            }
        }
        return Yii::$app->cache->get($this->key_access);
    }

    private function httpGet($url) {
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