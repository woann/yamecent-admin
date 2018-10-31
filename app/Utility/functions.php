<?php
function validateURL($URL) {
    $pattern = "/^(?:([A-Za-z]+):)?(\/{0,3})([0-9.\-A-Za-z]+)(?::(\d+))?(?:\/([^?#]*))?(?:\?([^#]*))?(?:#(.*))?$/";
    if(preg_match($pattern, $URL)){
        return true;
    } else{
        return false;
    }
}

/**
 * @Desc: 获取配置值
 * @Author: woann <304550409@qq.com>
 * @param $key
 * @return array
 */
function getConfig($key)
{
    if(is_array($key)){
        $res = \DB::table('admin_config')->whereIn('config_key',$key)->get();
        $data = [];
        if($res){
            foreach ($res as $v){
                $data[$v->config_key] = $v->config_value;
            }
        }
        return $data;
    }else{
        $res = \DB::table('admin_config')->where('config_key','=',$key)->first();
        if(!$res){
            return null;
        }
        return $res->config_value;
    }
}