<?php
namespace common\widgets\images;

use backend\components\uploader\QiniuUploader;
use Yii;
use yii\base\Action;

/**
 * 上传图片控制器
 */
class UploadAction extends Action
{

    public $uploadTo;
    public $uploadType;
    /**
     * ---------------------------------------
     * 上传base64格式的图片
     * @return void
     * ---------------------------------------
     */
    public function run(){
        $json = [
            'boo'  => false,
            'msg'  => '上传失败',
            'data' => [
                'id' => 0,
                'url' => '',
            ]
        ];
        $this->uploadType = empty($this->uploadType) ? 'file' : $this->uploadType;
        if($this->uploadType == 'base64'){
            $imgbase64 = Yii::$app->request->post('imgbase64');
            if (empty($imgbase64)) {
                $this->ajaxReturn($json);
            }
            preg_match('/^(data:\s*image\/(\w+);base64,)/', $imgbase64, $result);
            $type = $result[2];
            $type = $type == 'jpeg' ? 'jpg' : $type;
            $tempName = 'tmpfile.' . $type;
        }else{
            $type = null;
            $imgbase64 = null;
            $tempName = null;
        }

        switch ($this->uploadTo){
            case 'qiniu':
                $ak = Yii::$app->params['qiniu']['ak'];
                $sk = Yii::$app->params['qiniu']['sk'];
                $bucket = Yii::$app->params['qiniu']['bucket'];
                $up = new QiniuUploader($ak,$sk,$bucket);
                $_config = [
                    'uploadAllowFiles' => Yii::$app->params['upload']['fileAllowExt'],
                    'uploadAllowSize' => Yii::$app->params['upload']['fileMaxSize'],
                    'uploadFormatPath' => Yii::$app->params['upload']['path'],
                    'tempName' => $tempName
                ];
                $up->load($_config);
                if($imgbase64){
                    $tempName = $up->getFileName() . '.' . $type;
                }
                $info = $up->save($imgbase64,$tempName);
                break;
            case 'oss':
                break;
            case 'local':
                break;
        }
        if (isset($info) && $info['state'] == 'SUCCESS') {
            $json['data']['url'] = $info['result'];
            $json['data']['width'] = $info['width'];
            $json['data']['height'] = $info['height'];
            $json['boo']  = true;
            $json['msg']  = '上传成功';
        }
        $this->ajaxReturn($json);
    }

    /**
     * ----------------------------------
     * 保存一张图片到数据库
     * @param $url string 图片路径
     * @return array|boolean
     * ----------------------------------
     */
    public function savePic($url){
        $file_path = Yii::$app->params['upload']['path'].$url;
        $file_md5  = md5_file($file_path);
        $image = Picture::find()->where(['md5'=>$file_md5])->asArray()->one();
        if ($image) {
            unlink($file_path); // 图片已存在，删除该图片
            return $image;
        }
        $model = new Picture();
        $data['path'] = $url;
        $data['md5']  = $file_md5;
        $data['create_time'] = time();
        $data['status'] = 1;
        $model->setAttributes($data);
        if ($model->save()) {
            return $model->getAttributes();
        }
        return false;
    }
    
    public function ajaxReturn($data) {
        // 返回JSON数据格式到客户端 包含状态信息
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($data));
    }

}
