var qqq = new Qupload({
    textarea:'textarea'
});
function openAlert(){
    qqq.openAlert();
}
function closeAlert(){
    qqq.closeAlert();
}
var $create__guide_container = $('#create__guide_container');
var $insert_box_components = $('#insert_box_components');
function firstAddImg(type){
    if(!$create__guide_container.hasClass('--hide')){
        addImg(undefined,type);
    }
}

function firstAddText(){
    if(!$create__guide_container.hasClass('--hide')){
        addTextarea();
    }
}

function addTextarea(obj){
    qqq.addTextarea(obj);
}

function addImg(obj,type){
    if(obj != undefined){
        qqq.$imgAfterElem = obj;
        qqq.uploadType = 'add';
    }
    qqq.uploadFileType = type;
    qqq.openAlert();
}

function reedit(obj){
    qqq.reedit(obj);
}
function delEdit(obj) {

    qqq.delEdit(obj);
}

function makeSure(obj) {

    qqq.makeSure();
}

function reupload(obj){
    if(obj != undefined){
        qqq.$imgAfterElem = obj;
        qqq.uploadType = 'edit';
    }
    qqq.openAlert();
}

function uploadCover(obj) {
    qqq.$imgAfterElem = obj;
    qqq.uploadType = 'cover';
    qqq.openAlert();
}

function editComment(obj) {
    var $obj = $(obj);
    var $parent = $obj.parents('.__add_box_main_body__action_html');
    var isScrollComments = $parent.find('.__action_html__scroll_layout').length == 1,
        comment;
    if(isScrollComments){
        comment = prompt('每行小图标个数：', '');
    }else{
        comment = prompt('图片备注：', '');
    }
    if(comment){
        if(isScrollComments){
            $parent.data('comment',comment);
            const commentHtml = `<p class='dataComment'>当前每行显示：${comment}个小图标</p>`;
            $parent.find('.dataComment').remove();
            setTimeout(function(){
                $parent.append(commentHtml);
            },100);
        }else{
            $parent.find('.__action_html__img_layout img').data('comment',comment);
            $parent.find('.__action_html__img_layout .imgComment').text(comment);
        }
    }
}

function content_sort(){
    qqq.contentSort();
}

function removeInSort(obj) {
    $(obj).parents('.dad-draggable-area').remove();
}

function saveSort() {
    qqq.saveSort();
}

function cancelSort() {
    qqq.cancelSort();
}

function FileUploaded(file,info){
    if(info != undefined){
        if(info.error != undefined){
            alert(info.error);
        }else{
            var fileId = file.id;
            var response = info.response;
            var url = JSON.parse(response).data.url;
            qqq.uploadFiles[fileId] = {
                'id':fileId,
                'url':url
            };
        }
    }
}

function UploadComplete(uploader,files){
    let fileDataInit = new Promise(function(resolve, reject){

        let filesize = Object.keys(qqq.uploadFiles).length;
        let checkSize = 0;
        for(let i in qqq.uploadFiles){
            let filesrc = qqq.uploadFiles[i].url;
            let fileext = filesrc.substr(filesrc.lastIndexOf('.') + 1);
            if(fileext == 'mp4'){
                qqq.uploadFiles[i].type = 'video';
                checkSize++;
                if(checkSize == filesize){
                    resolve();
                }
            }else{
                let image = new Image();
                image.onload = function(){
                    fileext = image.width / image.height == 2 ? 'pano' : 'img';
                    qqq.uploadFiles[i].type = fileext;
                    checkSize++;
                    if(checkSize == filesize){
                        resolve();
                    }
                }
                image.onerror = function(){
                    reject('文件读取失败，请重试');
                }
                image.src = filesrc;
            }
        }
    });

    fileDataInit.then(function(){
        $(uploader.settings.container).find('.common_progress_bar').removeClass('__show');
        qqq.addImg();
    }).catch(function(err){
        alert(err);
        return false;
    });
}
var tj = true;
function next_step(obj){
    var $next_step = $(obj);
    var o_bt_text = $next_step.text();
    var postUrl = $next_step.data('posturl');
    var status = $next_step.data('status');
    var csrfParam = document.getElementsByTagName('meta')['csrf-param'].getAttribute('content');
    var csrfToken = document.getElementsByTagName('meta')['csrf-token'].getAttribute('content');
    if($insert_box_components.hasClass('--hide')){
        alert('请上传内容');
        return false;
    }
    qqq._ueditorDestory();
    var $add_html_layout_container = $('.add_html_sign');
    var html = '';
    var img_array = new Array();
    var video_array = new Array();
    var work_content = new Array();

    $add_html_layout_container.each(function(){
        var $this = $(this);
        var temp = {};
        if($this.find('.text_temp').length == 1){
            //text
            var text = $this.find('.text_temp').html();
            temp = {
                'type': 'textarea',
                'value': text
            };
            work_content.push(temp);
        } else if($this.find('.__action_html__scroll_layout').length == 1){
            var $scroll_imgs = $this.find('.scroll_img');
            var type = 'scroll';
            if($scroll_imgs.length == 0){
                $scroll_imgs = $this.find('.assembly_img');
                type = 'assembly';
            }
            var scroll_imgs = [];
            $scroll_imgs.each(function(){
                var $scroll = $(this);
                var _temp = $scroll.data('src');
                scroll_imgs.push(_temp);
            });
            temp = {
                'type': type,
                'value': scroll_imgs,
                'comment': $this.find('.__add_box_main_body__action_html').data('comment') || 0
            };
            work_content.push(temp);
        } else if($this.find('video').length == 1){
            var src = $this.find('video').attr('src');
            var cover = $this.find('video').data('cover');
            video_array.push(src);
            temp = {
                'type': 'video',
                'value': src,
                'cover': cover || ''
            };
            work_content.push(temp);

        } else if($this.find('img').length == 1){
            //img
            var src = $this.find('img').attr('src');
            var type = $this.find('img').data('type');
            var comment = $this.find('img').data('comment') || '';
            img_array.push(src);
            var _temp_img = '<img src="'+ src +'" data-type="'+ type +'" data-comment="'+ comment +'" />';
            temp = {
                'type': 'img',
                'value': src,
                'comment': comment
            };
            work_content.push(temp);
        }
    });
    if($add_html_layout_container.length == 0){
        alert('请上传相关内容');
        return false;
    }
    if(tj){
        $.ajax({
            url: postUrl,
            type: 'post',
            dataType: 'json',
            data:{csrfParam:csrfToken,'work_content':work_content,'status': status},
            beforeSend:function(){
                tj = false;
                $next_step.text('上传中，请稍后');
            },
            success:function(data){
                if(data.status == 0){
                    //成功
                    if(status == 0){
                        alert('上传成功');
                        setTimeout(function(){
                            window.location.href = data.data.backUrl;
                        },300);
                    }else{
                        alert('暂存成功');
                    }
                }else{
                    //失败
                    alert(data.error)
                }
                tj = true;
                $next_step.text(o_bt_text);
            },
            error:function(){}
        });
    }
}