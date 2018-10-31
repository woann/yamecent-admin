function upload(obj){
    obj.parents('.form-group').find('input').click();
}
$('.img-file').on("change",function(){
    var index = layer.load();
    var _this = $(this);
    var fd = new FormData();
    fd.append("image", _this.get(0).files[0]);
    fd.append("path",_this.attr('data-path'));
    var index = layer.load();
    $.ajax({
        url:"/admin/upload",
        type:"post",
        cache : false,
        contentType : false,
        processData : false,
        dataType: "json",
        data:fd,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success:function(res){
            layer.close(index);
            if(res.code == 200){
                _this.parents('.form-group').find('.img-yl').empty().append('<img src="'+res.data.filename+'">').show();
                _this.parents('.form-group').find('.image-path').val(res.data.filename);
                _this.parents('.form-group').find('.file-upload-info').val(res.data.filename);
            }else{
                layer.msg(res.msg);
            }
        },error:function(){
            layer.close(index);
        }
    });
})
$('input.required').blur(function(){
    if($(this).val() == ''){
        $(this).css('border','1px solid #dd4b39');
    }else{
        $(this).css('border','1px solid #ebedf2');
    }
})
function myRequest(url,type,data,success,error) {
    $.ajax({
        url:url,
        type:type,
        data:data,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType:"json",
        success:success,
        error:error
    })
}
function myConfirm(msg,confirm){
    layer.confirm(msg, {
        btn: ['确定','取消'] //按钮
    }, confirm, function(){
    });
}
$.fn.serializeObject=function(){
    var obj=new Object();
    $.each(this.serializeArray(),function(index,param){
        if(!(param.name in obj)){
            obj[param.name]=param.value;
        }
    });
    return obj;
};
function checkForm() {
    var mark = 0;
    $('input.required').each(function(){
        if($(this).val() == ''){
            mark = 1;
            $(this).css('border','1px solid #dd4b39');
        }else{
            $(this).css('border','1px solid #ebedf2');
        }
    });
    $('select.required').each(function(){
        if($(this).val() == ''){
            mark = 1;
            $(this).css('outline','1px solid #dd4b39');
        }else{
            $(this).css('outline','1px solid #ebedf2');
        }
    });
    if(mark == 1){
        layer.msg('所选字段不能为空', function(){
        });
        return false;
    }
    return true;
}
function cutStr(len){
    var obj=$('.len');
    for (i=0;i<obj.length;i++){
        var a = obj[i].innerHTML.length;
        if(a > len){
            obj[i].innerHTML=obj[i].innerHTML.substring(0,len)+'…';
        }
    }
}