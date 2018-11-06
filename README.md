# 欢迎使用yamecent-admin后台管理

## 项目简介
yamecent-admin是一款基于laravel框架进行封装的后台管理系统,其中包含：

 * rbac权限管理模块
 * 完整的ui组件(外部引入)
 * 图片上传,网络请求等常用的js公共函数
 * [演示地址][3]`管理员账号:admin 密码:yamecent666`
 * 持续维护中...

## 安装教程
 * 执行安装命令 `git clone https://github.com/woann/yamecent-admin`或者`composer create-project woann/yamecent-admin`
 * 导入数据到数据库,数据库文件在项目根目录下 yamecent-admin.sql
 * 修改数据库配置信息(根目录下.env文件)
 * 配置域名(按laravel项目正常配置即可,解析到public目录)
 * 初始超级管理员具有最高权限,不可删除
 * 如发现权限相关问题 执行 chown -R 用户名:用户组 项目目录
 * 访问域名,登录即可进入管理系统
 * UI参考地址: http://demo.cssmoban.com/cssthemes5/twts_141_PurpleAdmin/pages/ui-features/buttons.html

## js函数列表

| 函数 | 用途 |
| -------- | -------- |
| myRequest(url,type,data,success,error){} | 发起ajax请求(包含laravel的token验证,loading等) |
| function myConfirm(msg,confirm){} | 发起询问框 |
| checkForm(){} | 验证表单 |
| cutStr(){} | 限制td字数 |
| batch(url){} | 批量操作 |

## 富文本
 * html
 ```html
    <div class="form-group " id="text" style="display: none;">
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
    editor.create();
 ```
 * 示例
![富文本编辑器](https://www.woann.cn/data/uploads/20181106/4b46e3cb6f919a809de3d4767b87f3a6.png)

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
![批量操作](https://www.woann.cn/data/uploads/20181106/b9c768af7774621707f45cc328a3b8ae.png)

[1]: https://www.woann.cn
[2]: http://xjj.woann.cn
[3]: http://demo.woann.cn

## 部分截图

![admin](https://www.woann.cn/data/uploads/20181030/64edd12357e3d5012efd8aba1d71da69.png)
![admin](https://www.woann.cn/data/uploads/20181031/963a14bd20bcdd8fcb5a2e0cd5be2111.png)
![admin](https://www.woann.cn/data/uploads/20181031/366b35386620019dbe1052a3eee7b924.png)

作者 [@woann][1]  [@xjj][2]   
2018 年 10月 30日    
