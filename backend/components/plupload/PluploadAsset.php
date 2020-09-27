<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2018/8/21
 * Time: 下午5:17
 */

namespace backend\components\plupload;

use yii\web\AssetBundle;

class PluploadAsset extends AssetBundle
{

    public $sourcePath = '@backend/components/plupload/assets';
//    public $basePath = '@webroot';
//    public $baseUrl = '@web';

    public $js = [
        'js/plupload.full.min.js',
        'js/i18n/zh_CN.js',
    ];
    public $css = [
        'css/style.css'
    ];
    public $depends = [
        'backend\assets\AppAsset'
    ];

}