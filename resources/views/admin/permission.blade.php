@extends('base.base')
@section('base')
    <!-- 内容区域 -->
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                     <span class="page-title-icon bg-gradient-primary text-white mr-2">
                        <i class="mdi mdi-wrench"></i>
                    </span>
                    权限
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">系统设置</a></li>
                        <li class="breadcrumb-item active" aria-current="page">权限管理</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">权限列表</h4>
                            <p class="card-description">
                                <button type="button" class="btn btn-sm btn-gradient-success btn-icon-text" onclick="add()">
                                    <i class="mdi mdi-plus btn-icon-prepend"></i>
                                    添加权限
                                </button>
                            </p>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="10%">权限名</th>
                                    <th width="35%">路由</th>
                                    <th width="15%">创建时间</th>
                                    <th width="15%">更新时间</th>
                                    <th width="20%">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($list as $permission)
                                    <tr>
                                        <td>{{ $permission->id }}</td>
                                        <td>{{ $permission->name }}</td>
                                        <td style="word-wrap:break-word;word-break:break-all">
                                            @foreach($permission->routes as $route)
                                                <label class="badge badge-success">{{$route}}</label>
                                            @endforeach
                                        </td>
                                        <td>{{ $permission->created_at }}</td>
                                        <td>{{ $permission->updated_at }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-gradient-dark btn-icon-text" onclick="update({{ $permission->id }})">
                                                修改
                                                <i class="mdi mdi-file-check btn-icon-append"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-gradient-danger btn-icon-text" onclick="del({{ $permission->id }})">
                                                <i class="mdi mdi-delete btn-icon-prepend"></i>
                                                删除
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>

        function add(){
            layer.open({
                type: 2,
                title: '添加权限',
                shadeClose: true,
                shade: 0.8,
                area: ['70%', '90%'],
                content: '/admin/permission/add'
            });
        }

        function update(id){
            var page = layer.open({
                type: 2,
                title: '修改权限',
                shadeClose: true,
                shade: 0.8,
                area: ['70%', '90%'],
                content: '/admin/permission/update/'+id
            });
        }

        function del(id){
            myConfirm("删除操作不可逆,是否继续?",function(){
                myRequest("/admin/permission/del/"+id,"post",{},function(res){
                    layer.msg(res.msg)
                    setTimeout(function(){
                        window.location.reload();
                    },1500)
                });
            });
        }

    </script>

@endsection
