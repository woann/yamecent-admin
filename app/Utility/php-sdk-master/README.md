# 网易视频云PHP-SDK 说明

## 1 简介

PHP-SDK是用于服务器端点播上传的软件开发工具包，提供简单、便捷的方法，方便用户开发上传视频或图片文件的功能。

## 2 功能特性

1. 文件上传
2. 断点续传

## 3 开发准备

### 3.1 环境配置

1. PHP5.3以上支持Sqlite3
2. 安装composer使用PHP的类自动加载机制

### 3.2 类引入

```php
use VideoCloud\Storage\UploadManager;
```

## 4 使用说明

### 4.1 安装composer

1. windows手动下载安装 ，网址：https://getcomposer.org/doc/00-intro.md

2. 使用命令行安装详细见composer官网

   ps:如果打开cmd，输入：composer -version出现版本号则说明安装成功

3. 启动composer需要在项目的根目录下包含一个`composer.json`的文件

   #### composer.json

   ```
   {
       "name": "vcloud/php-sdk",
       "type": "library",
       "description": "VideoCloud Resource (Cloud) Storage SDK for PHP",
       "keywords": ["vcloud", "storage", "sdk", "cloud"],
       "homepage": "http://vcloud.163.com/",
       "license": "MIT",
       "authors": [
           {
               "name": "VideoCloud",
               "homepage": "http://vcloud.163.com/"
           }
       ],
       "require": {
           "php": ">=5.3.3"
       },
       "require-dev": {
           "phpunit/phpunit": "~4.0",
           "squizlabs/php_codesniffer": "~2.3"
       },
       "autoload": {
           "psr-4": {"VideoCloud\\": "phpSDK/src/VideoCloud"},
           "files": ["phpSDK/src/VideoCloud/functions.php"]
       }
   }
   ```

4. 在 `composer.json` 的 `autoload` 字段中增加自己的 autoloader

   **配置项说明：**

   * `VideoCloud\\`是命名空间的名称，后面的是根目录
   * `files`是需要自动加载的php文件

5. 也可以直接复制demo中的`composer.json`文件,但上面两个目录需要修改成项目相应目录

6. 启动类自动加载机制在文件的根目录下还有一个`autoload.php`文件

   ```php
   <?php

   function classLoader($class)
   {
       $path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
       $file = __DIR__ . '/src/' . $path . '.php';

       if (file_exists($file)) {
           require_once $file;
       }
   }
   spl_autoload_register('classLoader');

   require_once  __DIR__ . '/src/VideoCloud/functions.php';
   ```

### 4.2  初始化

接入视频云点播，需要拥有一对有效的 appKey 和 appSecret进行签名认证，可通过如下步骤获得：

1. 开通视频云点播服务；
2. 登陆视频云开发者平台，通过管理控制台->账户信息获取 appKey 和 appSecret。

在获取到 AppKey 和 AppSecret 之后，可按照如下方式进行初始化：

```js
$uploadMgr = new UploadManager();

$opt=array();
$opt["accessKey"]="your AppKey";
$opt["secretKey"]="your AppSecret";
$opt["trunkSize"]= 2 * 1024 * 1024;

$filePath2 = './birds.mp4';

list($ret, $err) = $uploadMgr->init($opt, $filePath2);
if ($err !== null) {
    die($err);
}
```

**配置项说明：**

1. $opt["accessKey"]：AppKey
2. $opt["secretKey"]：AppSecret
3. $opt["trunkSize"]：分片大小，最大4MB

### 4.3 文件上传

调用upload接口，传入init()返回值和文件路径即可完成文件上传，路径支持相对路径（相对于index.js文件）或绝对路径（推荐）。
示例：

```php
list($ret, $err) = $uploadMgr->upload($opt,$ret['ret'],$filePath);
```

**配置项说明：**

1. $ret['ret']为init()返回信息
2. $filePath文件相对index.js路径

### 4.4 断点续传

upload接口同时支持断点续传，只需传入$opt,init()返回值和文件路径调用upload接口即可，SDK会自动查询断点并进行续传。
示例：

```php
list($ret, $err) = $uploadMgr->upload($opt,$ret['ret'],$filePath);
```

**配置项说明：**

1. $ret['ret']为init返回信息
2. $filePath文件相对index.js路径

## 5 一个上传的例子

```php
<?php
require_once __DIR__ . '/autoload.php';


use VideoCloud\Storage\UploadManager;


$uploadMgr = new UploadManager();


$opt=array();
$opt["accessKey"]="your AppKey";
$opt["secretKey"]="your AppSecret";
$opt["trunkSize"]= 2 * 1024 * 1024;

$filePath = './birds.mp4';

list($ret, $err) = $uploadMgr->init($opt, $filePath);
if ($err !== null) {
    die($err);
}

list($ret, $err) = $uploadMgr->upload($opt,$ret['ret'],$filePath);
if ($err !== null) {
    var_dump($err);
} else {
    var_dump($ret);
}
```

## 6 版本更新记录

v1.0.1

1. Node-SDK初始版本，提供点播上传的基本功能，包括：文件上传、断点续传。

