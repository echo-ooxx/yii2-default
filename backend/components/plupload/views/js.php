<?php use yii\helpers\Json; ?>
var uploader_temp_file_lists = {};
var loaded_img_lists = {};
var _temp_data_lists = [];
var uploader_<?= $id ?> = new plupload.Uploader({
runtimes: 'html5,flash,silverlight,html4',
browse_button: '<?= $id ?>_pickfiles',
container: document.getElementById('<?= $id ?>_container'),

url: '<?php echo $uploadurl ?>',

filters: {
    max_file_size: '<?php echo $fileSizeLimit ?>',
    mime_types: [
        {title: "Files", extensions: "<?php echo $fileExtLimit ?>"}
    ],
    prevent_duplicates: true,
    max_file_count: <?php echo $fileNumLimit ?>
},
multipart: true,
multipart_params: $.parseJSON('<?php echo Json::encode($formData) ?>'),
file_data_name: 'FileData',
<?php if ($fileNumLimit > 1): ?>
    multi_selection: true,
<?php else: ?>
    multi_selection: false,
<?php endif; ?>

flash_swf_url: '<?php echo $asseturl ?>/Moxie.swf',

silverlight_xap_url: '<?php echo $asseturl ?>/Moxie.xap',

init: {
PostInit: function () {
    uploader_temp_file_lists = {};
    loaded_img_lists = {};
    <?php if(!$value):?>
        if(document.getElementById('<?= $id?>_queueLists')){
            document.getElementById('<?= $id?>_queueLists').innerHTML = "";
        }
    <?php endif;?>
    <?php if($value):?>
        var _temp_data = '<?= implode(',',$value);?>';
        _temp_data_lists = _temp_data.split(',');
    <?php endif;?>
    this.settings.container.setAttribute('data__queueLists_temp_item__delete-containerid',this.id);
},

FilesAdded: function (up, files) {
    <?php if($filetype == 'bt'):?>
        var _temp_data_size = parseInt($('<?= $inputContainer?>').children('.common_form_container_big_box_container').length);
    <?php else: ?>
        var _temp_data_size = parseInt($('#<?= $id?>_queueLists').find('.__temp_save_data').length);
    <?php endif;?>
    var now_size = 0;
    now_size = (_temp_data_size > 0) ? (_temp_data_size + parseInt(files.length)) : parseInt(files.length);
    if(now_size > up.settings.filters.max_file_count){
        var msg = '只能上传' + up.settings.filters.max_file_count + '个文件';
        alert(msg);
        up.splice(0,up.files.length);
        return false;
    }
//视频长度判断
let videoSize = 0;
let checkList = [];
for(let i in files){
let file = files[i];
if(file.type == 'video/mp4'){
checkList.push(file);
videoSize++
}
}

var checkVideoDuration = new Promise(function(resolve,reject){
let checkedFileSize = 0;
window.URL = window.URL || window.webkitURL;
if(videoSize <= 0){
resolve();
}else{
for (let x in checkList){
let checkFile = checkList[x];
let video = document.createElement('video');
video.preload = 'metadata';
video.onloadedmetadata = function(){
window.URL.revokeObjectURL(video.src);
let duration = Math.floor(video.duration);
if(<?= $videoDuration?> > 0 && duration > <?= $videoDuration?>){
let errorMsg = '视频长度不能超过<?= $videoDuration?>秒';
reject(errorMsg);
}else{
checkedFileSize++;
if(checkedFileSize == videoSize){
resolve();
}
}
};
let temp = checkFile.getNative();
video.src = URL.createObjectURL(temp);
}
}
});

checkVideoDuration.then(function(){
console.log('检查完毕');
uploader_<?= $id ?>.start();
}).catch(function(err){
alert(err);
up.splice(0,up.files.length);
return false;
});

},

FilesRemoved: FilesRemoved,

FileUploaded:function (up, file, info) {

    if(info != undefined){
        var response = info.response;
        var backresult = JSON.parse(response);
        if(backresult.error != undefined){
            alert(backresult.error);
            $('#'+file.id).find('.progress_name').text('上传失败,请重试');
            return;
        }else{
            var fileId = file.id;
            var url = backresult.url;
            uploader_temp_file_lists[fileId] = {
                'id':fileId,
                'url':url
            };

            if(loaded_img_lists.hasOwnProperty(fileId)){
                if(loaded_img_lists[fileId].readyState == 2){
                    fixImg(fileId);
                }else{
                    loaded_img_lists[fileId].onloadend = function(){
                        fixImg(fileId);
                    }
                }
            }else{
                //fixImg(fileId);
            }
        }
    }


    FileUploaded(file,info);
},

BeforeUpload: function(uploader,file){
    //上传之前
    <?php if($showQueue):?>
            var html = '';
        <?php if($filetype == 'bt'):?>
            html = $('#typeBtAssistDom').text();
            html = html.replace(/{fileid}/g,file.id);
            $('<?= $inputContainer?>').append(html);
        <?php else: ?>
                if(isImg(file.type)){
                    html = '<div id="'+ file.id +'" class="__queueLists_temp_item __temp_save_data"><img /><div class="__queueLists_temp_item__functions"><p class="filename">'+ file.name +'</p><a data-url="" class="__queueLists_temp_item__delete" data-id="'+file.id+'" href="javascript:void(0)"></a></div><div class="__queueLists_temp_item__progress"><div class="progress_bar progress_bar__begin"></div><p class="progress_name"></p></div></div>';
                    //是图片就开始预读
                    loaded_img_lists[file.id] = new FileReader();
                    loaded_img_lists[file.id].readAsDataURL(file.getNative());
                }else{
                    html = '<div id="'+ file.id +'" class="__queueLists_temp_item __temp_save_data"><i class="file_list__icon"></i><div class="file_list__name">'+file.name+'</div><div class="file_list__bar"><div class="progress_bar"></div></div><div class="progress_name"></div><a data-url="" class="__queueLists_temp_item__delete" data-id="'+file.id+'" href="javascript:void(0)"></a></div>';
                }
                $('#<?= $id?>_queueLists').append(html);
        <?php endif;?>
    <?php endif?>
},

UploadFile: function(uploader,file){
    $(this.settings.container).find('.common_progress_bar').css('width','0').addClass('__show');
    //UploadFile(uploader,file);
},

UploadProgress: function(uploader,file){
    var percent = file.percent;
    <?php if($showQueue):?>
        var all = 100;
        if(isImg(file.type)){
            $('#'+file.id).find('.progress_name').text('上传中..'+percent+'%');
            $('#'+file.id).find('.progress_bar').css('top',percent+'%');
            //这里是是bt类型
            if($('#'+file.id+'__upload_progress').length == 1){
                $('#'+file.id+'__upload_progress').css('width',percent+'%');
            }
            if(percent > 50){
                $('#'+file.id).find('.progress_name').css('color','#000');
            }
            if(percent == 100){
                $('#'+file.id).find('.progress_name').text('预览中..');
                $('#'+file.id+'__file_container').addClass('--uploaded');
            }
        }else{
            $('#'+file.id).find('.progress_name').text('上传中..'+percent+'%');
            $('#'+file.id).find('.progress_bar').css('width',percent+'%');
            if(percent == 100){
                $('#'+file.id).find('.progress_name').text('上传完成');
            }
        }
    <?php endif;?>
    <?php if($showCommonQueue):?>
        $(uploader.settings.container).find('.common_progress_bar').css('width',percent+'%');
        if(percent == 100){
            $(uploader.settings.container).find('.common_progress_bar').css('width',90+'%');
        }
    <?php endif;?>
},

UploadComplete: function(uploader,files){

    var $container = $(uploader.settings.container);
    var $input = $container.prev('input');

    var _temp_vale = $input.val();

    var val_arr = [];
    for (var i=0;i < files.length;i++){
        var fileId = files[i].id;
        val_arr.push(uploader_temp_file_lists[fileId].url);
        $('#'+fileId).find('.__queueLists_temp_item__delete').attr('data-url',uploader_temp_file_lists[fileId].url);
        <?php if($filetype == 'bt'):?>
            $('#'+fileId+'__url_input_container').val(uploader_temp_file_lists[fileId].url);
            $('#'+fileId+'__img_container').attr('src',uploader_temp_file_lists[fileId].url);
        <?php endif;?>
    }
    //var final_val = _temp_vale ? (_temp_vale + ',' + val_arr.join(',')) : val_arr.join(',');
    var final_val = val_arr.join(',');
    $input.val(final_val);

    UploadComplete(uploader,files);
},

Error: function(uploader,errObject){
    alert(errObject.message);
},
}
});

uploader_<?= $id ?>.init();

function FilesRemoved(uploader,files){
    var $container = $(uploader.settings.container);
    var $input = $container.prev('input');
    var input_val = $input.val();
    if(input_val){
        files.forEach((item,key) => {
            var fileId = item.id;
            var del_url = uploader_temp_file_lists[fileId].url;
            input_val = arrDel(del_url,input_val.split(','));
            input_val.join(',');
            delete(uploader_temp_file_lists[fileId]);
        });
    }
    input_val = (input_val == []) ? '' : input_val;
    $input.val(input_val);
}

function arrDel(del,arr){
    var pos = -1;
    for (var i in arr){
        if(arr[i] == del){
            pos = i;
        }
    }
    if(pos > -1){
        arr.splice(pos,1);
    }
    return arr;
}

function isImg(type){
    return /image\/\w+/.test(type);
}

function UploadError(){
console.log('UploadError');
}

function fixImg(fileId){
    var $dom = $('#'+fileId);
    $dom.find('.__queueLists_temp_item__progress').addClass('hide');
    $dom.find('img').attr('src',loaded_img_lists[fileId].result);
}

$(document).on('click','#<?= $id?>_queueLists .__queueLists_temp_item__delete',function(){
    var id = $(this).data('id');
    if(id != undefined){
        var files = uploader_<?= $id ?>.files;
        var index = 0;
        for(var i in files){
            if(files[i].id == id){
                index = i;
            }
        }
        //uploader_<?= $id ?>.removeFile(id);
        uploader_<?= $id ?>.splice(index,1);
    }else{
        var file_type = '<?= $filetype?>';
        switch (file_type){
            case 'image':
                var $__upload_bts__cover = $(this).parents('.__upload_bts__cover');
                break;
            case 'file':
                var $__upload_bts__cover = $(this).parents('.__upload_bts__file_container');
                break;
        }
        var del = $(this).data('url');
        var $input = $__upload_bts__cover.children('input');
        var vals = $input.val();
        vals = arrDel(del,vals.split(','));
        vals = (vals == [])?'':vals.join(',');
        $input.val(vals);
    }
    $(this).parents('.__queueLists_temp_item').remove();
});