@extends('base.base')
@section('base')
    <!-- 内容区域 -->
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">请填写回调url</h4>

                            <form class="forms-sample" id="form">

                                <div class="form-group">
                                    <label>视频上传回调</label>
                                    <div class="input-group col-xs-12">
                                        <input type="text" id="upload" class="form-control file-upload-info" value="{{$upload}}" placeholder="请输入视频上传回调地址">
                                        <span class="input-group-append">
                          <button type="button" onclick="commit('upload')" class="btn btn-gradient-primary btn-icon-text">
                          <i class="mdi mdi-settings btn-icon-prepend"></i>
                          提交
                        </button>
                        </span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>视频转码回调</label>
                                    <div class="input-group col-xs-12">
                                        <input type="text" id="transcode" class="form-control file-upload-info" value="{{$transcode}}" placeholder="请输入视频转码回调地址">
                                        <span class="input-group-append">
                          <button type="button" onclick="commit('transcode')" class="btn btn-gradient-primary btn-icon-text">
                          <i class="mdi mdi-settings btn-icon-prepend"></i>
                          提交
                        </button>
                        </span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>

        function commit(type){
            var url = $("#"+type).val();
            var data = {
                'type':type,
                'url':url,
            };
            myRequest("/set_callback_url","post",data,function(res){
                if(res.code == '200'){
                    layer.msg(res.msg)
                    setTimeout(function(){
                        location.reload();
                    },1500)
                }else{
                    layer.msg(res.msg)
                }
            },function(){
                layer.msg(res.msg, function(){});
            });
        }


    </script>
@endsection