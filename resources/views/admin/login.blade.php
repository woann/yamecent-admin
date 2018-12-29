<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>登录</title>
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
              <div class="brand-logo">
                <img src="{{ getConfig("admin_logo") }}">
              </div>
              <form class="pt-3">
                <div class="form-group">
                  <input type="text" class="form-control form-control-lg" id="account" placeholder="请输入账号">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" id="password" placeholder="请输入密码">
                </div>

                <div class="mb-2">
                  <button type="button" onclick="login()" class="btn btn-gradient-info btn-lg btn-block">
                    登录
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
  <script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>
  <script src="/assets/layer/layer.js"></script>
  <script src="/assets/js/common.js"></script>
</body>
<script>

    document.onkeydown=keyListener;
    function keyListener(e){
        if(e.keyCode == 13){
            login();
        }
    }

    function login(){
        var account = $("#account").val();
        var password = $("#password").val();
        if(!account || !password){
            layer.msg('账号和密码不能为空', function(){});
            return false;
        }
        var data = {
            'account':account,
            'password':password,
        };
        myRequest("/login","post",data,function(res){
            if(res.code == '200'){
                layer.msg(res.msg)
                setTimeout(function(){
                    window.location.href="/";
                },1500)
            }else{
                layer.msg(res.msg)
            }
        });
    }
</script>

</html>
