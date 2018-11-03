<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>安装</title>
    <link rel="stylesheet" href="/assets/vendors/iconfonts/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="/assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="shortcut icon" href="/assets/images/favicon.png" />
</head>

<body>
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
            <div class="row w-100">
                <div class="col-lg-4 mx-auto">
                    <div class="auth-form-light text-left p-5">
                       <h4>Yamecent-admin 安装</h4>

                        <form class="pt-3">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg" name="host" placeholder="Mysql 数据库地址">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg" name="database" placeholder="Mysql 数据库名">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg" name="username" placeholder="Mysql 用户名">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-lg" name="password" placeholder="Mysql 密码">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg" name="prefix" placeholder="Mysql 表前缀">
                            </div>
                            <br>
                            <br>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg" name="rootName" placeholder="管理员用户名">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-lg" name="rootPass" placeholder="管理员密码">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-lg" name="rootPassAgain" placeholder="管理员确认密码">
                            </div>

                            <div class="mb-2">
                                <button type="button" onclick="install()" class="btn btn-gradient-info btn-lg btn-block">
                                    安装
                                </button>
                            </div>
                            <div class="text-center mt-4 font-weight-light">

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/assets/vendors/js/vendor.bundle.base.js"></script>
<script src="/assets/vendors/js/vendor.bundle.addons.js"></script>
<script src="/assets/js/off-canvas.js"></script>
<script src="/assets/js/misc.js"></script>
<script src="http://cdn.bootcss.com/jquery/1.12.3/jquery.min.js"></script>
<script src="/layer/layer.js"></script>
<script src="/assets/js/common.js"></script>
</body>
<script>
    function install(){
        alert("111111");
    }
</script>

</html>
