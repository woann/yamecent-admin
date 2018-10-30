<?php
namespace VideoCloud\Storage;

use VideoCloud\Config;
use VideoCloud\Storage\ResumeUploader;
use VideoCloud\DB\MyDB;

/**
 * 主要涉及了资源上传接口的实现
 *
 */
final class UploadManager
{

    /**
     * 获得初始化信息
     *
     * @param {string} $filePath   上传文件的路径
     * @param {array} $opt     数组，包括三个信息[
     *                                                  "accessKey" => "<your accessKey string>",
     *                                                  "secretKey" => "<your secretKey string>"，
     *                                                  "trunkSize" => "<your size number>"
     *                                              ]
     *@return array    包含已上传文件的信息，类似：
     *                                              [
     *                                                  "bucket" => "<bucket string>",
     *                                                  "object" => "<object string>",
     *                                                  "xNosToken" => "<xNosToken string>"
     *                                              ]
     */

    public function init($opt,$filePath) 
    {
    
        if($opt["accessKey"]==null || $opt["secretKey"]==null){
            exit('请填写accessKey和secretKey');
        }
        if ($opt["trunkSize"] > Config::BLOCK_SIZE) {
            exit('分片大小超过最大限制（4MB），将设为上限值。');
        }
        $filepath = $filePath;
        $file = fopen($filePath, 'rb');
        if ($file === false) {
            throw new \Exception("文件不能打开", 1);
        }
        $stat = fstat($file);
        $size = $stat['size'];
        $fileNameArr = explode("/",$filePath);
        $fileName = end($fileNameArr);


        $data = fread($file, $size);
        fclose($file);
        if ($data === false) {
            throw new \Exception("文件不可读", 1);
        }

        return ResumeUploader::getInitData(
            $opt,
            $filePath,
            $fileName
        );
    }

     /**
     * 上传方法
     *
     * @param {array}  $initData   调用init返回的信息
     * @param {string} $filePath   文件路径
     */

    public function upload($pot,$initData,$filePath) 
    {   

        $DEBUG = false;//设置是否打印日志
        $nos_bucket = $initData['bucket'];
        $nos_object = $initData['object'];
        $nos_token = $initData['xNosToken'];
        $nos_context = '';


        //读取文件基本信息
        $file = fopen($filePath, 'rb');
        $stat = fstat($file);
        $size = $stat['size'];
        $mtime = $stat['mtime'];
        fclose($file);
        $fileInfo = array('filepath' =>$filePath , 'mtime' =>$mtime,'filesize' =>$size);


        //创建数据库
        $db = new MyDB();
           if(!$db){
              die($db->lastErrorMsg());
           }

        MyDB::createTable($db);



       //检查文件是否存在
        $fileExist = MyDB::checkExist($fileInfo,$db);
        if($fileExist){
            $fileData = MyDB::getFile($fileInfo,$db);
        }else{
            $fileData = array('filepath' => $filePath,
                'mtime' => $mtime,
                'filesize' => $size,
                'created' => time(),
                'nos_token' => $nos_token,
                'nos_object' => $nos_object,
                'nos_bucket' => $nos_bucket,
                'nos_context' =>'' 
             );
            MyDB::saveFile($fileData,$db);
        }


        //获得NDS      
        $NDS =  ResumeUploader::getNDS($fileData['nos_bucket']);
        $uploadIP = $NDS[0]['upload'][0];


        //获取上传断点位置
        $uploadOffset = 0;
        if ($fileExist && $fileData['nos_context']) {
            $uploadOffset = ResumeUploader::getUploadOffset($uploadIP, $fileData);
            $uploadOffset = $uploadOffset[0]['offset']? $uploadOffset[0]['offset']:0;
            if($DEBUG==true){
                print_r('last offset:'.$uploadOffset.'</br>');
            }
            
        }
        if($DEBUG==true){
            print_r('upload start...</br>');
            print_r('upload init progress:'. round(($uploadOffset / $fileData['filesize'] )* 100 ,2) . '%</br>');
        }

        //上传分片
        while ($uploadOffset < $fileData['filesize']) {
            $fileData['offset'] = $uploadOffset;
            $fileData['finish'] = "false";
            if ($uploadOffset + Config::BLOCK_SIZE >= $fileData['filesize']) {
                $fileData['finish'] = "true";
            }
            list($ret, $err) = ResumeUploader::uploadTrunk($uploadIP, $fileData);
            if ($err !== null) {
                return($err);
            }
            
            $fileData['nos_context'] = $ret['context'];
            if ($fileData['nos_context'] && strtolower($fileData['nos_context'])!== 'null') {
                MyDB::saveContext($fileData,$db);
            }
            $uploadOffset = $uploadOffset + Config::BLOCK_SIZE;
            if ($fileData['finish']==="true") {
                $uploadOffset = $fileData['filesize'];
            }
            if($DEBUG==true){
                print_r('upload progress:'.  round(($uploadOffset / $fileData['filesize'] )* 100 ,2) . '%</br>');
            }
        }

  
        //删除文件
        MyDB::removeFile($fileData,$db);

        //获取视频返回信息，视频返回vid，图片返回imgId
        list($ret, $err) = ResumeUploader::getResInfo($pot,$initData);
        if ($err !== null) {
           return array(null,$err);
        } else {
            return array($ret,null);
        }

    }


}
