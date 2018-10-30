<?php

namespace App\Http\Controllers\Admin;
use App\Utility\Video;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    /**
     * @Desc: 设置上传/转码回调地址
     * @Author: woann <304550409@qq.com>
     * @return mixed
     */
    public function setCallbackUrl(Request $request)
    {
        $url = $request->input('url');
        $type = $request->input('type');
        if(!in_array($type,['upload','transcode'])){
            return $this->json(500,"param type is miss");
        }
        if(!validateURL($url)){
            return $this->json(500,"url格式不正确");
        }
        $param = ["callbackUrl" => $url];
        if($type == "upload"){
            $api = "/app/vod/upload/setcallback";
        }else{
            $api = "/app/vod/transcode/setcallback";
        }
        $res = Video::Request($api,$param);
        $res = json_decode($res);
        if(!$res || $res->code != 200){
            return $this->json(500,"操作失败!");
        }
        return $this->json(200,"操作成功!");
    }
}
