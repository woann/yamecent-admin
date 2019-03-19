@extends('base.base')
@section('base')

    <!-- 内容区域 -->
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">修改权限</h4>
                            <p class="card-description">
                                Update Permission
                            </p>
                            <form class="forms-sample">
                                <div class="form-group">
                                    <label for="name">权限名</label>
                                    <input type="text" class="form-control" id="name" value="{{ $permission->name }}">
                                </div>

                                <div class="form-group" style="width: 100%;height: 200px;">
                                    <select class="form-control" id="selectL" name="selectL" multiple="multiple" style="width:40%;height:200px;float: left">
                                        @foreach($uncheck_routes as $route)
                                            <option value="{{$route->uri}}">{{$route->uri}}</option>
                                        @endforeach
                                    </select>

                                    <button type="button" id="toright" class="btn btn-gradient-primary btn-sm" style="margin-left: 60px;margin-top: 80px;"> > </button>
                                    <button type="button" id="toleft" class="btn btn-gradient-primary btn-sm" style="margin-top: 80px;"> < </button>

                                    <select class="form-control" id="selectR" name="selectR" multiple="multiple" style="width:40%;height:200px;float: right">
                                        @foreach($check_routes as $route)
                                            <option value="{{$route->uri}}">{{$route->uri}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="button" onclick="commit({{ $permission->id }})" class="btn btn-sm btn-gradient-primary btn-icon-text">
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
        var leftSel = $("#selectL");
        var rightSel = $("#selectR");
        $("#toright").bind("click",function(){
            leftSel.find("option:selected").each(function(){
                $(this).remove().appendTo(rightSel);
            });
        });

        $("#toleft").bind("click",function(){
            rightSel.find("option:selected").each(function(){
                $(this).remove().appendTo(leftSel);
            });
        });

        leftSel.dblclick(function(){
            $(this).find("option:selected").each(function(){
                $(this).remove().appendTo(rightSel);
            });
        });
        rightSel.dblclick(function(){
            $(this).find("option:selected").each(function(){
                $(this).remove().appendTo(leftSel);
            });
        });


        function commit(id){
            var selVal = [];
            rightSel.find("option").each(function(){
                selVal.push(this.value);
            });
            // selVals = selVal.join(",");
            // if(selVals==""){
            //     layer.msg('请选择路由', function(){});
            // }
            if (selVal.length === 0) {
                layer.msg('请选择路由', function(){});
            }
            var name = $("#name").val();
            if(name==""){
                layer.msg('您必须输入权限名称', function(){});
            }
            var data = {
                'name':name,
                'routes':selVal,
            };
            myRequest("/admin/permission/update/"+id,"post",data,function(res){
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
