<?php

use App\AdminConfig;
function validateURL($URL)
{
    $pattern = "/^(?:([A-Za-z]+):)?(\/{0,3})([0-9.\-A-Za-z]+)(?::(\d+))?(?:\/([^?#]*))?(?:\?([^#]*))?(?:#(.*))?$/";
    if (preg_match($pattern, $URL)) {
        return true;
    } else {
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
    return AdminConfig::getValue($key);
}
