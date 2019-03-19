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
                    管理员
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">系统设置</a></li>
                        <li class="breadcrumb-item active" aria-current="page">管理员管理</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">管理员列表</h4>
                            <p class="card-description">
                                <button type="button" class="btn btn-sm btn-gradient-success btn-icon-text" onclick="add()">
                                    <i class="mdi mdi-plus btn-icon-prepend"></i>
                                    添加管理员
                                </button>
                            </p>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>头像</th>
                                    <th>昵称</th>
                                    <th>账号</th>
                                    <th>角色</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($admins as $admin)
                                    <tr>
                                        <td>{{ $admin->id }}</td>
                                        <td>
                                        @if($admin->avatar)
                                            <img class="avatar" src="{{ $admin->avatar ?? "" }}" alt="image">
                                        @endif
                                        </td>
                                        <td>{{ $admin->nickname }}</td>
                                        <td>{{ $admin->account }}</td>
                                        <td>
                                        @foreach($admin->roles as $role)
                                            {{ $role->name }}
                                        @endforeach
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-gradient-dark btn-icon-text" onclick="update({{ $admin->id }})">
                                                修改
                                                <i class="mdi mdi-file-check btn-icon-append"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-gradient-danger btn-icon-text" onclick="del({{ $admin->id }})">
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
                title: '添加管理员',
                shadeClose: true,
                shade: 0.8,
                area: ['70%', '90%'],
                content: '/admin/administrator/add'
            });
        }

        function update(id){
            var page = layer.open({
                type: 2,
                title: '修改管理员',
                shadeClose: true,
                shade: 0.8,
                area: ['70%', '90%'],
                content: '/admin/administrator/update/'+id
            });
        }

        function del(id){
            myConfirm("删除操作不可逆,是否继续?",function(){
                myRequest("/admin/administrator/del/"+id,"post",{},function(res){
                    layer.msg(res.msg)
                    setTimeout(function(){
                        window.location.reload();
                    },1500)
                });
            });
        }

    </script>

@endsection
