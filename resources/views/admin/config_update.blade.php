@extends('base.base')
@section('base')
    <!-- 内容区域 -->
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">请修改配置信息</h4>
                            {{--<p class="card-description">--}}
                            {{--Basic form elements--}}
                            {{--</p>--}}
                            <form class="forms-sample" id="form">
                                <div class="form-group">
                                    <label >* 配置描述</label>
                                    <input type="text"  class="form-control required" name="name" placeholder="配置描述" value="{{ $config->name }}">
                                </div>
                                <div class="form-group">
                                    <label >* 关键字(key)</label>
                                    <input type="text"  class="form-control required" name="config_key" placeholder="key" value="{{ $config->config_key }}">
                                </div>
                                @if($config->type == "string")
                                    <div class="form-group" id="string">
                                        <label >* 配置值(value)</label>
                                        <input type="text" name="config_value" class="form-control value-input" name="config_key" placeholder="key" value="{{ $config->config_value }}">
                                    </div>
                                @elseif($config->type == "image")
                                    <div class="form-group" id="image">
                                        <label>* 配置值(value)</label>
                                        <input type="file" class="file-upload-default img-file" data-path="config">
                                        <input type="hidden" class="image-path value-input" name="config_value" value="{{ $config->config_value }}">
                                        <div class="input-group col-xs-12">
                                            <input type="text" class="form-control file-upload-info" disabled="" value="{{ $config->config_value }}">
                                            <span class="input-group-append">
                                                <button class="file-upload-browse btn btn-gradient-primary" onclick="upload($(this))" type="button">上传</button>
                                            </span>
                                        </div>
                                        <div class="img-yl" style="display: block;">
                                            <img src="{{ $config->config_value }}" alt="">
                                        </div>
                                    </div>
                                @else
                                    <div class="form-group " id="text">
                                        <label >* 配置值(value)</label>
                                        <textarea  placeholder="请在此处编辑内容" name="config_value" id="editor" style="height:400px;max-height:400px;overflow: hidden">{{ $config->config_value }}</textarea >
                                    </div>
                                @endif
                                <button type="button" onclick="commit({{ $config->id }})" class="btn btn-sm btn-gradient-primary btn-icon-text">
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
        @if($config->type == "text")
        var editor = new wangEditor('editor');
        // 上传图片（举例）
        editor.config.uploadImgUrl = "/admin/wangeditor/upload";
        // 隐藏掉插入网络图片功能。该配置，只有在你正确配置了图片上传功能之后才可用。
        editor.config.hideLinkImg = false;
        editor.create();
        @endif
        function commit(id){
            if(!checkForm()){
                return false;
            }
            var data = $("#form").serializeObject();
            myRequest("/admin/config/update/"+id,"post",data,function(res){
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
