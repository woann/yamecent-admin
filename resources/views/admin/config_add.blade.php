@extends('base.base')
@section('base')
    <!-- 内容区域 -->
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">请填写配置信息</h4>
                            {{--<p class="card-description">--}}
                            {{--Basic form elements--}}
                            {{--</p>--}}
                            <form class="forms-sample" id="form">
                                <div class="form-group">
                                    <label >* 配置描述</label>
                                    <input type="text"  class="form-control required" name="name" placeholder="配置描述">
                                </div>
                                <div class="form-group">
                                    <label >* 关键字(key)</label>
                                    <input type="text"  class="form-control required" name="config_key" placeholder="key">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword4">* 选择配置类型</label>
                                    <select class="form-control required" id="type" name="type">
                                        <option value="string">字符串</option>
                                        <option value="image">图片</option>
                                        <option value="text">富文本</option>
                                    </select>
                                </div>
                                <div class="form-group" id="string">
                                    <label >* 配置值(value)</label>
                                    <input type="text" name="config_value" class="form-control value-input"  placeholder="key">
                                </div>
                                <div class="form-group" id="image" style="display: none;">
                                    <label>* 配置值(value)</label>
                                    <input type="file" class="file-upload-default img-file" data-path="config">
                                    <input type="hidden" class="image-path value-input">
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control file-upload-info" disabled="">
                                        <span class="input-group-append">
                                            <button class="file-upload-browse btn btn-gradient-primary" onclick="upload($(this))" type="button">上传</button>
                                        </span>
                                    </div>
                                    <div class="img-yl">
                                    </div>
                                </div>
                                <div class="form-group " id="text" style="display: none;">
                                    <label >* 配置值(value)</label>
                                    <textarea  placeholder="请在此处编辑内容"  id="editor" style="height:400px;max-height:400px;overflow: hidden"></textarea >
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
        $(document).on('change','#type',function(){
            if($(this).val() == 'string'){
                $('#string').show().find('input').attr('name','config_value');
                $('#text').hide().find('textarea').removeAttr('name');
                $('#image').hide().find('.value-input').removeAttr('name');
            }else if($(this).val() == 'text'){
                $('#text').show().find('textarea').attr('name','config_value');
                $('#string').hide().find('input').removeAttr('name');
                $('#image').hide().find('.value-input').removeAttr('name');
            }else if($(this).val() == 'image'){
                $('#string').hide().find('input').removeAttr('name');
                $('#text').hide().find('textarea').removeAttr('name');
                $('#image').show().find('.value-input').attr('name','config_value');
            }
        })
        var editor = new wangEditor('editor');
        // 上传图片（举例）
        editor.config.uploadImgUrl = "/admin/wangeditor/upload";
        // 隐藏掉插入网络图片功能。该配置，只有在你正确配置了图片上传功能之后才可用。
        editor.config.hideLinkImg = false;
        editor.create();
        function commit(){
            if(!checkForm()){
                return false;
            }
            var data = $("#form").serializeObject();
            myRequest("/admin/config/add","post",data,function(res){
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