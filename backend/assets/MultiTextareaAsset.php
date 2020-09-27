<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2019/12/25
 * Time: 4:35 PM
 */

namespace backend\assets;


use yii\web\AssetBundle;

class MultiTextareaAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'multi-textarea/plugins/dad/jquery.dad.css',
        'multi-textarea/css/index.css'
    ];

    public $js = [
        'multi-textarea/plugins/dad/jquery.dad.min.js',
        'multi-textarea/plugins/ueditor/ueditor.config.js',
        'multi-textarea/plugins/ueditor/ueditor.all.js',
        'multi-textarea/plugins/dad/jquery.dad.min.js',
        'multi-textarea/js/multi-upload.js',
        'multi-textarea/js/multi-textarea.js'
    ];

    public $depends = [
        'backend\components\plupload\PluploadAsset'
    ];
}