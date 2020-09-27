<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2019/1/30
 * Time: 2:16 PM
 */

namespace frontend\components\plupload\uploader;

abstract class Uploader{

    public $config;
    public $originalName;
    public $file;

    abstract public function save($src,$dest);

    //初始化相关配置
    public function load($config){

        $this->config = $config;
        $this->file = current($_FILES);
        $this->originalName = is_array($this->file['name']) ? current($this->file['name']) : $this->file['name'];

    }

    //获取新文件名
    public function getFileName(){

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
     * 获取文件扩展
     * @return string
     */
    private function getFileExt(){
        return strtolower(strrchr($this->originalName,'.'));
    }

}

?>
