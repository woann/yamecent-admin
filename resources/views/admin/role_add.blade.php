@extends('base.base')
@section('base')
    <!-- 内容区域 -->
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">请填写角色信息</h4>
                            {{--<p class="card-description">--}}
                            {{--Basic form elements--}}
                            {{--</p>--}}
                            <form class="forms-sample" id="form">
                                <div class="form-group">
                                    <label >*角色名称</label>
                                    <input type="text"  class="form-control required" name="name" placeholder="角色名称">
                                </div>
                                <div class="form-group">
                                    <label >角色描述</label>
                                    <textarea class="form-control" name="description" rows="4"></textarea>
                                </div>
                                <div class="form-group">
                                    <div class="form-check col-md-1 col-sm-1" style="display: inline-block;">
                                        <label class="form-check-label" style="margin-left: 0">
                                            *选择权限
                                        </label>
                                    </div>
                                    <div class="form-check col-md-2 col-sm-2" style="display: inline-block;">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input all">
                                            全选
                                            <i class="input-helper"></i>
                                        </label>
                                    </div>
                                    <br>
                                    @foreach($permissions as $permission)
                                        <div class="form-check col-md-2 col-sm-2" style="display: inline-block;">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input permission" value="{{ $permission->id }}">
                                                {{ $permission->name }}
                                                <i class="input-helper"></i>
                                            </label>
                                        </div>
                                    @endforeach
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

        $('.all').on("click",function(){
            if(this.checked) {
                $("input[type='checkbox']").prop('checked',true);
            }else {
                $("input[type='checkbox']").prop('checked',false);
            }
        });
        function commit(){
            if(!checkForm()){
                return false;
            }
            var data = $("#form").serializeObject();
            var permissions =  new Array();
            $('.permission:checked').each(function(index){
                permissions[index] = $(this).val();
            })
            data.permissions = permissions;
            myRequest("/admin/role/add","post",data,function(res){
                layer.msg(res.msg)
                setTimeout(function(){
                    parent.location.reload();
                },1500)
            });
        }
        function cancel() {
            parent.location.reload();
        }
    </script>
@endsection
