<?php
namespace App\Utility;
use VideoCloud\Http\Client;

class Video{
    static public function Request($url,$params = [])
    {
        $url = "https://vcloud.163.com".$url;
        $app_key = config("user.Video.AppKey");
        $app_secret = config("user.Video.AppSecret");
        $nonce = (string)rand(0,pow(10, 16));
        $time = (string)round(time()/ 1000);
        $sha1 = (string)sha1($app_secret.$nonce.$time);
        $headers = array('AppKey' =>$app_key ,'Nonce' =>$nonce ,'CurTime' =>$time,'CheckSum' =>$sha1,'Content-type'=>'application/json;charset=UTF-8');
        $body_ = json_encode($params);
        $response = Client::post($url, $body_, $headers);
        return $response->body;
    }
}