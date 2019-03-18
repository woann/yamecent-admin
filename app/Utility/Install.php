<?php

namespace App\Utility;

use Dotenv\Dotenv;
use Illuminate\Container\Container;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class Install
{
    const INSTALL_FILE = '.' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'install.lock';

    public static function hasLock()
    {
        return file_exists(self::getInstallLockPath());
    }

    public static function lock()
    {
        touch(self::getInstallLockPath());
    }

    public static function getEnvFileDic()
    {
        $contents   = new Collection(file(self::getEnvFilePath()));
        $contentDic = $contents->reduce(function ($contentDic, $content) {
            if (is_string($content) && $content !== PHP_EOL) {
                $explodeArr = explode('=', str_replace(PHP_EOL, '', $content));
                if (count($explodeArr) > 1) {
                    list($key, $value) = $explodeArr;
                } else {
                    $key   = $explodeArr[0];
                    $value = '';
                }
                $contentDic[$key] = $value;
            }
            return $contentDic;
        }, []);
        return new Collection($contentDic);
    }

    public static function saveEnvFileDic(Collection $contents)
    {
        $contentArray = $contents->map(function ($value, $index) {
            return "{$index}={$value}";
        })->toArray();

        $content = implode($contentArray, PHP_EOL);
        file_put_contents(self::getEnvFilePath(), $content);
    }

    private static function getEnvFilePath()
    {
        return Container::getInstance()->environmentPath() . DIRECTORY_SEPARATOR .
        Container::getInstance()->environmentFile();
    }

    private static function getInstallLockPath()
    {
        return Container::getInstance()
            ->make('path.storage') . DIRECTORY_SEPARATOR . self::INSTALL_FILE;
    }
}
