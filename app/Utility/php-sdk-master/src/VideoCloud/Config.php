<?php
namespace VideoCloud;

final class Config
{
    const SDK_VER = '1.0.0';

    const BLOCK_SIZE = 4194304; //4*1024*1024 分块上传块大小，该参数为接口规格，不能修改
    const GET_INIT_URL = 'http://vcloud.163.com/app/vod/upload/init'; //获取bucket信息地址
    const GET_IP_URL = 'http://wanproxy.127.net/lbs?version=1.0&bucketname='; //获取上传DNS地址
    const GET_RES_URL = 'https://vcloud.163.com/app/vod/video/query';//获取vid信息

}
