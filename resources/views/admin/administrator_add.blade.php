@extends('base.base')
@section('base')
    <!-- 内容区域 -->
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">请填写管理员信息</h4>

                            <form class="forms-sample" id="form">
                                <div class="form-group">
                                    <label>头像上传</label>
                                    <input type="file" class="file-upload-default img-file" data-path="avatar">
                                    <input type="hidden" name="avatar" class="image-path">
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control file-upload-info" disabled="">
                                        <span class="input-group-append">
                                            <button class="file-upload-browse btn btn-gradient-primary" onclick="upload($(this))" type="button">上传</button>
                                        </span>
                                    </div>
                                    <div class="img-yl">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="nickname">*昵称</label>
                                    <input type="text"  class="form-control required" name="nickname" placeholder="请输入管理员昵称">
                                </div>

                                <div class="form-group">
                                    <label for="account">*账号</label>
                                    <input type="text"  class="form-control required" name="account" placeholder="请输入账号">
                                </div>

                                <div class="form-group">
                                    <label for="password">*密码</label>
                                    <input type="password" id="password"  class="form-control required" name="password" placeholder="请输入密码">
                                </div>

                                <div class="form-group">
                                    <label for="password">*确认密码</label>
                                    <input type="password" id="password_verify"  class="form-control required" name="password_verify" placeholder="请再次输入密码">
                                </div>

                                <div class="form-group">
                                    <label for="role">*角色</label>
                                    <select id="roles-selector" class="form-control form-control-lg" multiple="multiple">
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}">{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="button" onclick="commit()" class="btn btn-sm btn-gradient-primary btn-icon-text">
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


        function commit(){
            if($("#password").val() != $("#password_verify").val()){
                layer.msg('两次密码输入不一致', function(){});
            }
            if(!checkForm()){
                return false;
            }
            var data = $("#form").serializeObject();
            data.roles = []
            var rolesSelector = document.querySelector('select#roles-selector')
            for(opt of rolesSelector) {
                if(opt.selected) {
                    data.roles.push(opt.value)
                }
            }
            myRequest("/admin/administrator/add","post",data,function(res){
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
