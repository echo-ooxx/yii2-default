<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2018/8/22
 * Time: 上午9:36
 */

namespace frontend\components\plupload\uploader;

class LocalUploader
{
    private $originalName;          //原始文件名
    private $fileSize;              //文件大小
    private $fileType;              //文件类型
    private $fullName;              //文件完整名字，含路径
    private $filePath;              //文件完整路径文件大小超出网站限制
    private $fileName;              //新文件名称
    private $config;                //配置信息
    private $stateInfo;             //状态信息
    private $stateMap = [
        'SUCCESS',
        "文件大小超出 upload_max_filesize 限制",
        "文件大小超出 MAX_FILE_SIZE 限制",
        "文件未被完整上传",
        "没有文件被上传",
        "上传文件为空",
        "ERROR_TMP_FILE"           => "临时文件错误",
        "ERROR_TMP_FILE_NOT_FOUND" => "找不到临时文件",
        "ERROR_SIZE_EXCEED"        => "文件大小超出网站限制",
        "ERROR_TYPE_NOT_ALLOWED"   => "文件类型不允许",
        "ERROR_CREATE_DIR"         => "目录创建失败",
        "ERROR_DIR_NOT_WRITEABLE"  => "目录没有写权限",
        "ERROR_FILE_MOVE"          => "文件保存时出错",
        "ERROR_FILE_NOT_FOUND"     => "找不到上传文件",
        "ERROR_WRITE_CONTENT"      => "写入文件内容错误",
        "ERROR_UNKNOWN"            => "未知错误",
        "ERROR_DEAD_LINK"          => "链接不可用",
        "ERROR_HTTP_LINK"          => "链接不是http链接",
        "ERROR_HTTP_CONTENTTYPE"   => "链接contentType不正确"
    ];



    public function __construct($config,$type = 'upload'){

        $this->config = $config;
////        上传的类型
//        switch ($type){
//            case 'upload':
////                普通文件
//                $this->upFile();
//                break;
//            case 'base64':
////                base64编码格式
//                $this->upBase64();
//                break;
//        }

    }

    public function doUp(){
        $this->upFile();
    }

    /**
     * 上传普通文件
     */
    private function upFile(){

        $file = current($_FILES);

        if(!$file){
            $this->stateInfo = $this->getStateInfo('ERROR_FILE_NOT_FOUND');
            return;
        }

        $tmpName = (is_array($file['tmp_name'])) ? current($file['tmp_name']) : $file['tmp_name'];
        $fileError = (is_array($file['error'])) ? current($file['error']) : $file['error'];

        if($fileError){
            $this->stateInfo = $this->getStateInfo($fileError);
            return;
        }elseif (!file_exists($tmpName)){
            $this->stateInfo = $this->getStateInfo('ERROR_TMP_FILE_NOT_FOUND');
            return;
        }elseif (!is_uploaded_file($tmpName)){
            $this->stateInfo = $this->getStateInfo('ERROR_TMP_FILE');
            return;
        }

        $this->originalName = is_array($file['name']) ? current($file['name']) : $file['name'];
        $this->fileSize = is_array($file['size']) ? current($file['size']) : $file['size'];
        $this->fileType = $this->getFileExt();
        $this->fullName = $this->getFullName();
        $this->filePath = $this->getFilePath();
        $this->fileName = $this->getFileName();
        $dirname = dirname($this->filePath);

        if(!$this->checkType()){
            $this->stateInfo = $this->getStateInfo('ERROR_TYPE_NOT_ALLOWED');
            return;
        }
//        文件大小是否超过限制
        if(!$this->checkSize()){
            $this->stateInfo = $this->getStateInfo('ERROR_SIZE_EXCEED');
            return;
        }
        if(!file_exists($dirname) && !mkdir($dirname,0777,true)){
            $this->stateInfo = $this->getStateInfo('ERROR_CREATE_DIR');
            return;
        }elseif (!is_writeable($dirname)){
            $this->stateInfo = $this->getStateInfo('ERROR_DIR_NOT_WRITEABLE');
            return;
        }

        if(!(move_uploaded_file($tmpName,$this->filePath) && file_exists($this->filePath))){
            $this->stateInfo = $this->getStateInfo('ERROR_FILE_MOVE');
        }else{
            $this->stateInfo = $this->stateMap['0'];
        }



    }

    private function upBase64(){}

    /**
     * 上传错误检查
     * @param $errCode
     * @return mixed
     */
    private function getStateInfo($errCode){
        return !$this->stateMap[$errCode] ? $this->stateMap['ERROR_UNKNOWN'] : $this->stateMap[$errCode];
    }

    /**
     * 获取文件扩展
     * @return string
     */
    private function getFileExt(){
        return strtolower(strrchr($this->originalName,'.'));
    }

    /**
     * 重命名文件
     * @return string
     */
    private function getFullName(){
        //替换日期时间
        $t = time();
        $d = explode('-',date('Y-y-m-d-H-i-s'));
        $format = $this->getConfigFormatPath();
        $new_file_name = '';
        if($format){
            $format = str_replace("{user}",'user'.$this->config['uid'],$format);
            $format = str_replace("{yyyy}", $d[0], $format);
            $format = str_replace("{yy}", $d[1], $format);
            $format = str_replace("{mm}", $d[2], $format);
            $format = str_replace("{dd}", $d[3], $format);
            $format = str_replace("{hh}", $d[4], $format);
            $format = str_replace("{ii}", $d[5], $format);
            $format = str_replace("{ss}", $d[6], $format);
            $format = str_replace("{time}",$t,$format);

            //过滤文件名的非法自负,并替换文件名
            $oriName = substr($this->originalName, 0, strrpos($this->originalName, '.'));
            $oriName = preg_replace("/[\|\?\"\<\>\/\*\\\\]+/", '', $oriName);
            $format = str_replace("{filename}", $oriName, $format);
            //替换随机字符串
            $randNum = mt_rand(1, 1000000000) . mt_rand(1, 1000000000);
            if (preg_match("/\{rand\:([\d]*)\}/i", $format, $matches)) {
                $format = preg_replace("/\{rand\:[\d]*\}/i", substr($randNum, 0, $matches[1]), $format);
            }
            $ext = $this->getFileExt();
            $new_file_name = $format . $ext;
        }
        return $new_file_name;

    }

    /**
     * 根据文件类型获取初始化路径
     * @return null
     */
    private function getConfigFormatPath(){
        return $this->config['uploadFormatPath'];
    }

    /**
     * 获取完整路径
     * @return string
     */
    private function getFilePath(){

        $fullname = $this->fullName;

        if (substr($fullname, 0, 1) != '/') {
            $fullname = '/' . $fullname;
        }

        return $this->config['rootPath'] . $fullname;

    }

    /**
     * 获取文件名
     * @return bool|string
     */
    private function getFileName(){
        return substr($this->filePath, strrpos($this->filePath, '/') + 1);
    }

    /**
     * 检查文件格式
     * @return bool
     */
    private function checkType(){
        return in_array($this->fileType,$this->config['uploadAllowFiles']);
    }

    /**
     * 检查文件大小
     * @return bool
     */
    private function checkSize(){
        return $this->fileSize <= $this->config['uploadAllowSize'];
    }

    public function getFileInfo(){

        return [
            'state' => $this->stateInfo,
            'url' => strchr($this->fullName,'/')
        ];
    }
}