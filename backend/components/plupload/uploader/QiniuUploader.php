<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2019/1/30
 * Time: 1:42 PM
 */

namespace frontend\components\plupload\uploader;


use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Yii;

class QiniuUploader extends Uploader
{

    public $accessKey;
    public $secretKey;
    public $bucket;

    public $policy = [
        'returnBody' => '{"name": $(fname),"size": $(fsize),"type": $(mimeType),"w": $(imageInfo.width),"h": $(imageInfo.height),"hash": $(etag)}',
    ];

    protected $auth;
    protected $uploadMgr;


    public function __construct($accessKey,$secretKey,$bucket)
    {
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
        $this->bucket = $bucket;

        $this->auth = new Auth($this->accessKey,$this->secretKey);
        $this->uploadMgr = new UploadManager();
    }

    public function save($src = null,$dest = null){
        $uptoken = $this->auth->uploadToken($this->bucket,null,3600,$this->policy);
        $src = $src ? $src : $this->file['tmp_name'];
        $dest = $dest ? $dest : $this->getFileName();
        list($res, $err) = $this->uploadMgr->putFile($uptoken,$dest,$src);
        if($err !== null){
            return [
                'state' => $err,
                'result' => ''
            ];
        }else{
            $url = Yii::$app->params['qiniu']['cname'] . $dest;
            return [
                'state' => 'SUCCESS',
                'result' => $url,
                'size' => $res['size']
            ];
        }
    }

}