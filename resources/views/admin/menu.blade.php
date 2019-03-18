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
                    菜单
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">系统设置</a></li>
                        <li class="breadcrumb-item active" aria-current="page">菜单管理</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">菜单列表</h4>
                            <p class="card-description">
                                <button type="button" class="btn btn-sm btn-gradient-success btn-icon-text" onclick="add()">
                                    <i class="mdi mdi-plus btn-icon-prepend"></i>
                                    添加菜单
                                </button>
                            </p>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>菜单名称</th>
                                    <th>菜单链接</th>
                                    <th>所属角色</th>
                                    <th>创建时间</th>
                                    <th>更新时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($list as $menu)
                                    <tr>
                                        <td>
                                        @if(count($menu->children))
                                            <i class="mdi mdi-menu-down menu-switch" id="{{$menu->id}}" state="on"></i>
                                        @endif
                                            <i class="{{ $menu->icon }}">　</i>{{ $menu->name }}
                                        </td>
                                        <td>{{ $menu->url }}</td>
                                        <td>
                                            @foreach($menu->roles as $role)
                                                <label class="badge badge-success">{{ $role->name }}</label>
                                            @endforeach
                                        </td>
                                        <td>{{ $menu->created_at }}</td>
                                        <td>{{ $menu->updated_at }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-gradient-dark btn-icon-text" onclick="update({{ $menu->id }})">
                                                修改
                                                <i class="mdi mdi-file-check btn-icon-append"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-gradient-danger btn-icon-text" onclick="del({{ $menu->id }})">
                                                <i class="mdi mdi-delete btn-icon-prepend"></i>
                                                删除
                                            </button>
                                        </td>
                                    </tr>
                                    @if(count($menu->children))
                                        @foreach($menu->children as $childmenu)
                                            <tr class="pid-{{ $menu->id }}">
                                                <td>　　　　 {{ $childmenu->name }}</td>
                                                <td>{{ $childmenu->url }}</td>
                                                <td>
                                                    @foreach($childmenu->roles as $role)
                                                        <label class="badge badge-success">{{ $role->name }}</label>

                                                    @endforeach
                                                </td>
                                                <td>{{ $childmenu->created_at }}</td>
                                                <td>{{ $menu->updated_at }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-gradient-dark btn-icon-text" onclick="update({{ $childmenu->id }})">
                                                        修改
                                                        <i class="mdi mdi-file-check btn-icon-append"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-gradient-danger btn-icon-text" onclick="del({{ $childmenu->id }})">
                                                        <i class="mdi mdi-delete btn-icon-prepend"></i>
                                                        删除
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
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
            var page = layer.open({
                type: 2,
                title: '添加菜单',
                shadeClose: true,
                shade: 0.8,
                area: ['70%', '90%'],
                content: '/admin/menu/add'
            });
        }
        function update(id){
            var page = layer.open({
                type: 2,
                title: '修改菜单',
                shadeClose: true,
                shade: 0.8,
                area: ['70%', '90%'],
                content: '/admin/menu/update/'+id
            });
        }
        function del(id){
            myConfirm("删除操作不可逆,是否继续?",function(){
                myRequest("/admin/menu/del/"+id,"post",{},function(res){
                    layer.msg(res.msg)
                    setTimeout(function(){
                        window.location.reload();
                    },1500)
                });
            });
        }

        $('.menu-switch').click(function(){
            id = $(this).attr('id');
            state = $(this).attr('state');
            console.log(id)
            console.log(state)
            if(state == "on"){
                $('.pid-'+id).hide();
                $(this).attr("state","off")
                $(this).removeClass('mdi-menu-down').addClass('mdi-menu-right');
            }else{
                $('.pid-'+id).show();
                $(this).attr("state","on")
                $(this).removeClass('mdi-menu-right').addClass('mdi-menu-down');
            }
        })
    </script>
@endsection
