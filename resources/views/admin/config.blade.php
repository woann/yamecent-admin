@extends('base.base')
@section('base')
    <!-- 内容区域 -->
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                     <span class="page-title-icon bg-gradient-primary text-white mr-2">
                        <i class="mdi mdi-settings"></i>
                    </span>
                    配置
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">配置管理</a></li>
                        <li class="breadcrumb-item active" aria-current="page">配置列表</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">配置列表</h4>
                            <div class="col-lg-9" style="float: left;padding: 0;">
                                <button type="button" class="btn btn-sm btn-gradient-success btn-icon-text" onclick="add()">
                                    <i class="mdi mdi-plus btn-icon-prepend"></i>
                                    添加配置
                                </button>
                            </div>
                            <div class="col-lg-3" style="float: right">
                                <form action="">
                                    <div class="form-group" >
                                        <div class="input-group col-xs-3">
                                            <input type="text" name="wd" class="form-control file-upload-info" placeholder="请输入关键字" value="{{ $wd }}">
                                            <span class="input-group-append">
                                                <button class=" btn btn-sm btn-gradient-primary" type="submit"><i class="mdi mdi-account-search btn-icon-prepend"></i>
                                                    搜索
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th width="10%">配置描述</th>
                                    <th width="10%">配置类型</th>
                                    <th width="10%">key</th>
                                    <th width="25%">value</th>
                                    <th width="10%">创建时间</th>
                                    <th width="10%">更新时间</th>
                                    <th width="15%">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($list as $k=>$v)
                                    <tr>
                                        <td>{{ $v->name }}</td>
                                        <td>
                                            @if($v->type == "string")
                                                字符串
                                            @elseif($v->type == "image")
                                                图片
                                            @else
                                                富文本
                                            @endif
                                        </td>
                                        <td>{{ $v->config_key }}</td>
                                        <td @if($v->type != "image") class="len" @endif>
                                            @if($v->type == "image")
                                                <div>
                                                    <img src="{{ $v->config_value }}" class="config-img" alt="">
                                                </div>
                                            @else
                                                {{ $v->config_value }}
                                            @endif
                                        </td>
                                        <td>{{ $v->created_at }}</td>
                                        <td>{{ $v->updated_at }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-gradient-dark btn-icon-text" onclick="update({{ $v->id }})">
                                                修改
                                                <i class="mdi mdi-file-check btn-icon-append"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-gradient-danger btn-icon-text" onclick="del({{ $v->id }})">
                                                <i class="mdi mdi-delete btn-icon-prepend"></i>
                                                删除
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="box-footer clearfix">
                                总共 <b>{{ $list->appends(["wd"=>$wd])->total()  }}</b> 条,分为<b>{{ $list->lastPage() }}</b>页
                                {!! $list->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function(){
            cutStr(50);
        });
        function add(){
            var page = layer.open({
                type: 2,
                title: '添加配置',
                shadeClose: true,
                shade: 0.8,
                area: ['70%', '90%'],
                content: '/admin/config/add'
            });
        }
        function update(id){
            var page = layer.open({
                type: 2,
                title: '修改配置',
                shadeClose: true,
                shade: 0.8,
                area: ['70%', '90%'],
                content: '/admin/config/update/'+id
            });
        }
        function del(id){
            myConfirm("删除操作不可逆,是否继续?",function(){
                myRequest("/admin/config/del/"+id,"post",{},function(res){
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
