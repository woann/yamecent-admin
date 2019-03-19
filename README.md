# 欢迎使用yamecent-admin后台管理
![LOGO](http://upload-images.jianshu.io/upload_images/9160823-5ca4487bc91ab57a.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

[![Latest Stable Version](https://poser.pugx.org/woann/yamecent-admin/v/stable.svg)](https://packagist.org/packages/woann/yamecent-admin)
[![Total Downloads](https://poser.pugx.org/woann/yamecent-admin/downloads.svg)](https://packagist.org/packages/woann/yamecent-admin)
[![License](https://poser.pugx.org/woann/yamecent-admin/license.svg)](https://github.com/woann/yamecent-admin/blob/master/LICENSE)
[![Php Version](https://img.shields.io/badge/php-%3E=7.2-brightgreen.svg?maxAge=2592000)](https://secure.php.net/)
[![Laravel Version](https://img.shields.io/badge/laravel-%3E=5.7-brightgreen.svg?maxAge=2592000)](https://laravel.com/)

## 项目简介
yamecent-admin是一款基于laravel框架进行封装的后台管理系统,其中包含：

* rbac权限管理模块
* 完整的[UI组件](http://demo.cssmoban.com/cssthemes5/twts_141_PurpleAdmin/pages/ui-features/buttons.html)
* 自定义配置管理
* 图片上传,网络请求等常用的js公共函数
* 项目弹出层引用了layer,可直接使用layer
* 持续维护中...



## 安装教程
[@tksmai][3]这位朋友对代码和安装方式进行了优化
* 克隆代码库`git clone https://github.com/tksmai/yamecent-admin.git` 
* 执行命令 `composer install && composer run-script post-root-package-install && composer run-script post-create-project-cmd` 安装 laravel 框架，依赖库，并做配置文件初始化
* 配置域名(按laravel项目正常配置即可,解析到public目录)
* 如发现权限相关问题 执行 chown -R 用户名:用户组 项目目录
* 访问域名,登录即可进入管理系统
* 首次访问域名时会跳转至安装页面![安装页面.jpg](https://upload-images.jianshu.io/upload_images/14769055-a5c3bae19726a891.jpg?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)
* 填写完数据表配置之后，点击安装
* 安装完之后就可以使用刚刚提交的管理员账号密码登录了（如果您想重新安装，要将 `<应用目录>/storage/app/install.lock` 文件删掉，重新访问网址即可）


## 请求(不想用这个请求方法可以自行用ajax)
```javascript
var data = {id:1};//参数
myRequest("/admin/config/add","post",data,function(res){
    //请求成功回调
    layer.msg("提示信息");//弹出提示
    //15秒后刷新父页面
    setTimeout(function(){
        parent.location.reload();
    },1500)
});
```
>请求失败回调默认封装了取消loading层的操作，如果想自定义请求失败的回调的话，自行修改public/assets/js/common.js文件中的myRequest方法

## 表单不为空验证
input添加`require`class
```
 <div class="form-group" id="string">
    <label >* 测试</label>
    <input type="text" name="test" class="form-control require"  placeholder="">
</div>
```

```
check = checkForm();//验证表单，如果带有require的input为空，则边框变为红色并弹出提示
if(!check){
    return false;
}
```

## 富文本
 * html
 ```html
    <div class="form-group ">
        <label >富文本</label>
        <textarea  placeholder="请在此处编辑内容"  id="editor" style="height:400px;max-height:400px;overflow: hidden"></textarea >   
    </div>
 ```
 * javascript
 ```javascript
    var editor = new wangEditor('editor');
    editor.config.uploadImgUrl = "/admin/wangeditor/upload";
    // 隐藏掉插入网络图片功能。该配置，只有在你正确配置了图片上传功能之后才可用。
    editor.config.hideLinkImg = false;
    //关闭导航栏浮动
    editor.config.menuFixed = false;
    editor.create();
 ```
 * 示例
![富文本编辑器](http://upload-images.jianshu.io/upload_images/14769055-b42c1b3b4f4ab979.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

## 时间选择器
* yamecent-admin的时间选择器是引入flatpickr插件，支持多语言，各种格式的时间选择。[查看文档](https://flatpickr.js.org)
* html
 ```html
    <div class="form-group">
       <label >*生效时间</label>
       <input type="text"  class="form-control required calendar" name="time" placeholder="请选择时间">
    </div>
 ```
 * javascript
 ```javascript
     $(".calendar").flatpickr({
            dateFormat:"Y-m-d H:i:S",
            enableTime:true,
            enableSeconds:true
            //更多配置百度搜索flatpickr即可找到
        });
 ```
  * 示例

![时间选择器](https://upload-images.jianshu.io/upload_images/9160823-ee7dedaeb832a49c.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)


## 列表批量操作
* html
 ```html
    <!--按钮-->
    <button type="button" class="btn btn-sm btn-gradient-danger btn-icon-text" onclick="batch('/admin/user/del/')">
       <i class="mdi mdi-delete btn-icon-prepend"></i>批量删除
    </button>
```
```html
    <!--全选复选框-->
    <tr>
        <th width="3%">
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input batch-all">
                </label>
            </div>
         </th>
        ...
    </tr>
```
```html
    <!--列表复选框-->
    <tr>
        <td>
            <div class="form-check">
               <label class="form-check-label">
                   <input type="checkbox" class="form-check-input td-check" value="{{ $v->id }}">
               </label>
            </div>
        </td>
        ...
    </tr>
 ```

 * 示例
![批量操作](http://upload-images.jianshu.io/upload_images/14769055-62ba575064933680.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

[1]: https://www.woann.cn
[2]: http://xjj.woann.cn
[3]: https://github.com/tksmai

## 部分截图
* 数据统计

![数据统计](https://upload-images.jianshu.io/upload_images/9160823-33fd684515b11c2f.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

* 自定义配置（字符串，图片，富文本多种数据格式,可通过getConfig("key")的方式获取配置值）

![自定义配置](https://upload-images.jianshu.io/upload_images/9160823-bc9b710eaecf7ef7.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

* 权限管理

![权限管理](https://upload-images.jianshu.io/upload_images/9160823-48859283e45b6fd2.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

* 菜单管理

![菜单管理](https://upload-images.jianshu.io/upload_images/9160823-07eed87c32fc721c.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

* 管理员

![管理员](https://upload-images.jianshu.io/upload_images/9160823-4b14ee0d83ab20da.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

* 角色

![角色](https://upload-images.jianshu.io/upload_images/9160823-f75c5aa058bf7e77.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)


作者 [@woann][1]  [@xjj][2]   [@tksmai][3]
2018 年 10月 30日    
