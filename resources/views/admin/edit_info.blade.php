@extends('base.base')
@section('base')
    <!-- 内容区域 -->
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">请修改您的个人信息</h4>
                            <form class="forms-sample" id="form">

                                <div class="form-group">
                                    <label>修改头像</label>
                                    <input type="file" class="file-upload-default img-file" data-path="avatar">
                                    <input type="hidden" name="avatar" class="image-path" value="{{$admin->avatar}}">
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control file-upload-info" disabled="" value="{{$admin->avatar}}" placeholder="选择图片">
                                        <span class="input-group-append">
                                            <button class="file-upload-browse btn btn-gradient-primary" onclick="upload($(this))" type="button">上传</button>
                                        </span>
                                    </div>
                                    <div class="img-yl" style="display: block;">
                                        <img src="{{$admin->avatar}}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="nickname">*昵称</label>
                                    <input type="text"  class="form-control required" name="nickname" value="{{$admin->nickname}}">
                                </div>

                                <div class="form-group">
                                    <label for="password">*密码</label>
                                    <input type="password" id="password"  class="form-control" name="password" value="{{$admin->clear_password}}">
                                </div>

                                <div class="form-group">
                                    <label for="password">*确认密码</label>
                                    <input type="password" id="password_verify"  class="form-control" name="password_verify" value="{{$admin->clear_password}}">
                                </div>

                                <button type="button" onclick="commit({{$admin->id}})" class="btn btn-sm btn-gradient-primary btn-icon-text">
                                    <i class="mdi mdi-file-check btn-icon-prepend"></i>
                                    提交
                                </button>

                                <button type="button" onclick="cancel()" class="btn btn-sm btn-gradient-warning btn-icon-text">
                                    <i class="mdi mdi-reload btn-icon-prepend"></i>
                                    取消
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>

        function commit(id){
            if($("#password").val() != $("#password_verify").val()){
                layer.msg('两次密码输入不一致', function(){});
            }
            if(!checkForm()){
                return false;
            }
            var data = $("#form").serializeObject();
            myRequest("/edit/info/"+id,"post",data,function(res){
                if(res.code == '200'){
                    layer.msg(res.msg)
                    setTimeout(function(){
                        parent.location.reload();
                    },1500)
                }else{
                    layer.msg(res.msg)
                }
            });
        }
        function cancel() {
            parent.location.reload();
        }
    </script>
@endsection
