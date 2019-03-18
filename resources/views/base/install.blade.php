<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>安装</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/iconfonts/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
</head>
<style>
    #error,.error,#success,.success {
        background: #D83E3E;
        color: #fff;
        padding: 15px 20px;
        border-radius: 4px;
        margin-bottom: 20px;
    }
    #success {
        background:#3C5675;
    }

    #error a, .error a {
        color:white;
        text-decoration: underline;
    }
</style>
<body>
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
            <div class="row w-100">
                <div class="col-lg-4 mx-auto">
                    <div class="auth-form-light text-left p-5">
                       <h4>Yamecent-admin 安装</h4>

                        <form class="pt-3">
                            @if($errorMsg)
                                <div class="error">
                                    {{ $errorMsg }}
                                </div>
                            @endif
                            <div id="error" style="display:none"></div>
                            <div id="success" style="display:none"></div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg" name="mysqlHost" placeholder="Mysql 数据库地址" >
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg" name="mysqlDatabase" placeholder="Mysql 数据库名" >
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg" name="mysqlUsername" placeholder="Mysql 用户名" >
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-lg" name="mysqlPassword" placeholder="Mysql 密码" >
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg" name="mysqlPrefix" placeholder="Mysql 表前缀 例：ya_" >
                            </div>
                            <br>
                            <br>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg" name="adminUsername" placeholder="管理员用户名" >
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-lg" name="adminPassword" placeholder="管理员密码" >
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-lg" name="adminPasswordAgain" placeholder="管理员确认密码" >
                            </div>

                            <div class="mb-2">
                                <button type="submit" class="btn btn-gradient-info btn-lg btn-block">
                                    安装
                                </button>
                            </div>
                            <div class="text-center mt-4 font-weight-light">
                                @csrf
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('assets/vendors/js/vendor.bundle.addons.js') }}"></script>
<script src="{{ asset('assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('assets/js/misc.js') }}"></script>
<script src="http://cdn.bootcss.com/jquery/1.12.3/jquery.min.js"></script>
<script src="{{ asset('assets/layer/layer.js') }}"></script>
<script src="{{ asset('assets/js/common.js') }}"></script>
</body>

<script>
    var REQUEST_SUCCESS = 200;
    $(function () {
        $("form :input:first").select();
        $("form").on("submit", function (e) {
            var index = layer.load();
            e.preventDefault();
            var $button = $(this).find("button")
                .text("安装中...")
                .prop("disabled", true);
            var payload = $(this).serializeArray();
            payload = payload.filter(function(input) {
                return input.value.length > 0;
            });

            var step1Callback = function (ret) {
                if (ret.code === REQUEST_SUCCESS) {
                    layer.close(index);
                    $("#error").hide();
                    $("#success").text("配置文件已写入，即将开始安装...").show();
                    setTimeout(function(){
                        $.post("{{ route('startInstall') }}", payload)
                            .done(step2Callback)
                            .fail(failCallback)
                    },1000);
                    localStorage.setItem("fastep", "installed");
                } else {
                    layer.close(index);
                    $("#error").show().text(ret.msg);
                    $button.prop("disabled", false).text("点击安装");
                    $("html,body").animate({
                        scrollTop: 0
                    }, 500);
                }
            }
            var step2Callback = function (ret) {
                if (ret.code === REQUEST_SUCCESS) {
                    layer.close(index);
                    $("#error").hide();
                    $("#success").text("安装成功！即将跳转至登录页！").show();
                    setTimeout(function(){
                        location.href = "/login";
                    },2000);
                    localStorage.setItem("fastep", "installed");
                } else {
                    layer.close(index);
                    $("#error").show().text(ret.msg);
                    $button.prop("disabled", false).text("点击安装");
                    $("html,body").animate({
                        scrollTop: 0
                    }, 500);
                }
            }

            var failCallback = function (data) {
                layer.close(index);
                $("#error").show().text("发生错误:\n\n" + data.responseText);
                $button.prop("disabled", false).text("点击安装");
                $("html,body").animate({
                    scrollTop: 0
                }, 500);
            }

            $.post("{{ route('setEnviroment') }}", payload)
                .done(step1Callback)
                .fail(failCallback);
            return false;
        });
    });
</script>
</html>
