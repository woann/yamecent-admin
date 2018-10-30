<?php
namespace VideoCloud\Storage;

use VideoCloud\Http\Client; 
use VideoCloud\Http\Error;
use VideoCloud\Config;

final class ResumeUploader
{


    /**
     * 获取$bucket、$xNosToken等信息
     * @param {String} filePath 文件路径
     * @param {String} filename 文件名
     * @param {array} opt 用户上传信息
     */
    public static function getInitData(
        $opt,
        $filePath,
        $fileName
    ) {

        $NONCE = (string)rand(0,pow(10, 16));
        $CUR_TIME = (string)round(time()/ 1000);
        $SHA1 = (string)self::getCheckSum($opt["secretKey"], $NONCE , $CUR_TIME);
        $headers = array('AppKey' =>$opt["accessKey"] ,'Nonce' =>$NONCE ,'CurTime' =>$CUR_TIME,'CheckSum' =>$SHA1,'Content-type'=>'application/json;charset=UTF-8');
        $body = array('originFileName'=>$fileName);
        $body_ = json_encode($body);

        $response = Client::post(Config::GET_INIT_URL, $body_, $headers);
        if (!$response->ok()) {
            return array(null, new Error($response));
        }
        return array($response->json(), null);
    }


    /**
     * 获取校验信息
     * @param {String} $appSecret
     * @param {String} $nonce 随机值
     * @param {String} $curTime 当前时间（秒数）
     */
    public static function getCheckSum(
        $appSecret,
        $nonce,
        $curTime
    ) {
        $item = $appSecret.$nonce.$curTime;
        return sha1($item);
    }


    /**
     * 获取上传地址
     * @param {String} $nos_bucket 桶名
     */
    public static function getNDS(
        $nos_bucket
    ) {
        $headers = array('Content-type'=>'application/json;charset=UTF-8');
        $url = Config::GET_IP_URL.$nos_bucket;
        $response = Client::get($url,$headers);
        if (!$response->ok()) {
            return array(null, new Error($response));
        }
        return array($response->json(), null);
    }


    /**
     * 获取上传断点位置
     * @param {String} $uploadIP 上传地址
     * @param {array}  $fileData 文件信息
     */
    public static function getUploadOffset(
        $uploadIP,
        $fileData
    ) {
        $url  = $uploadIP . '/' .$fileData['nos_bucket'] . '/' . $fileData['nos_object'] . '?uploadContext&version=1.0&context=' . $fileData['nos_context'];
        $headers = array('x-nos-token'=>$fileData['nos_token'],'Content-type'=>'application/json;charset=UTF-8');
        $response = Client::get($url,$headers);
        if (!$response->ok()) {
            return array(null, new Error($response));
        }
        return array($response->json(), null);
    }

    /**
     * 获取返回信息
     * @param {String} $objectNames 上传文件的对象名列表
     */
    public static function getResInfo(
        $opt,
        $initData
    ) {
        $NONCE = (string)rand(0,pow(10, 16));
        $CUR_TIME = (string)round(time()/ 1000);
        $SHA1 = (string)self::getCheckSum($opt["secretKey"], $NONCE , $CUR_TIME);

        $url  = Config::GET_RES_URL;
        $headers = array('AppKey' =>$opt["accessKey"] ,'Nonce' =>$NONCE ,'CurTime' =>$CUR_TIME,'CheckSum' =>$SHA1,'Content-type'=>'application/json;charset=UTF-8');
        $object = $initData['object'];

        $objectNames=array($object);
        $data = array();
        $data["objectNames"] = $objectNames;      

        $response = Client::post($url,json_encode($data),$headers);
        if (!$response->ok()) {
            return array(null, new Error($response));
        }
        $res = $response->json();
        return array($res['ret']["list"][0], null);
    }


    /**
     * 上传分片
     * @param {String} $uploadIP 上传地址
     * @param {Object} $fileData 文件信息
     */
    public static function uploadTrunk(
        $uploadIP,
        $fileData
    ) {
        $headers = array('x-nos-token'=>$fileData['nos_token'],'Content-type'=>'application/json;charset=UTF-8');
        $param = '?version=1.0&offset=' . $fileData['offset'] . '&complete=' . $fileData['finish'] . '&context=' . $fileData['nos_context'];
        $url  = $uploadIP . '/' . $fileData['nos_bucket'] . '/' . $fileData['nos_object'] . $param;
//        print_r("上传参数:".$param.'</br>');
//        print_r("上传地址:".$url.'</br>');
        $trunkLength = min(Config::BLOCK_SIZE, $fileData['filesize'] - $fileData['offset']);
//        print_r("上传分片大小:".$trunkLength.'</br>');

        //读取文件上传信息
        $file = fopen($fileData['filepath'], 'rb');
        fseek($file, $fileData['offset'], SEEK_SET);
        $data = fread($file, $trunkLength);
        fclose($file);
        if ($data === false) {
            throw new \Exception("file read failed", 1);
        }

        $response = Client::post($url,$data,$headers);
        if (!$response->ok()) {
            return array(null, new Error($response));
        }
        return array($response->json(), null);
    }

}
