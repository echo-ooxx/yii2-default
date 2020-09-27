<?php
/**
 * Created by PhpStorm.
 * User: leezhang
 * Date: 2017/12/24
 * Time: 上午10:18
 */

namespace common\dog;

use yii;
use yii\web\Cookie;

class CookieHelper{

    public static function setCookie($name,$value,$expire = 0){
        $cookie = Yii::$app->response->cookies;
        $cookie_data = new Cookie();
        $cookie_data->name = $name;
        $cookie_data->value = $value;
        if($expire > 0){
            $cookie_data->expire = time() + $expire;
        }
        $cookie->add($cookie_data);
    }

    public static function getCookie($name){
        $cookie = Yii::$app->request->cookies;
        return $cookie->getValue($name);
    }

    public static function hasCookie($name){
        $cookies = Yii::$app->request->cookies;
        return $cookies->has($name);
    }

    public static function deleteCookie($name){
        $cookie = Yii::$app->request->cookies;
        $cookie->remove($name);
    }

}