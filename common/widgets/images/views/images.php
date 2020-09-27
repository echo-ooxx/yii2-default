<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model yii\db\ActiveRecord */
/* @var $saveDB integer */
/* @var $attribute string */
/* @var $url string */

/* 判断是否保存到数据库 */
$saveDB = isset($saveDB)?$saveDB:1;
/* 图集处理 */
$data   = $model->{$attribute};
$field  = Html::getInputName($model,$attribute);
$albums = array();
if (!empty($data)) {
    $albums = is_array($data) ? $data : explode(',', $data);
}
$img_map = [
    '.jpg',
    '.jpeg',
    '.png',
    '.gif'
];
?>
<!-- image表图集 -->
<style>
.fileupload-item {width: 100px; height: 100px;position: relative;margin-right:5px;}
.fileupload-del {position: absolute;bottom:5px;left:40%;display:none;}
.fileupload-text {color: #f00;}
</style>
<div class="fileinput fileinput-new">
    <div style="margin-bottom:10px;">
        <span class="btn red btn-outline btn-file">
            <span class="fileupload-new">上传图片</span>
            <input multiple type="file" class="default uploadImages<?=$saveDB?>"/>
        </span>
        <span class="fileupload-text" style="display: inline"></span>
    </div>
    <div class="fileupload-list">
        <?php if($albums && is_array($albums)): ?>
        <?php foreach($albums as $g): ?>
            <?php
            if (false) {

            } else {
                $picture['id']   = $g;
                $picture['path'] = $g;
            }
            $count = count(explode('?',$g));
            if($count > 1){
                list($info,$params) = explode('?',$g);
            }else{
                $info = $g;
            }

            $ext = strrchr($info,'.');
            ?>
            <?php if(in_array($ext,$img_map)):?>
                    <div class="fileupload-item thumbnail">
                        <img src="<?=$picture['path']?>" />
                        <span class="fileupload-del">删除</span>
                        <input type="hidden" name="<?=$field?>[]" value="<?=$picture['id']?>" />
                    </div>
            <?php else: ?>
                    <div class="fileupload-item thumbnail">
                        <p>其他格式文件,格式为<?= $ext?></p>
                        <span class="fileupload-del">删除</span>
                        <input type="hidden" name="<?=$field?>[]" value="<?=$picture['id']?>" />
                    </div>
            <?php endif;?>

        <?php endforeach ?>
        <?php endif ?>
    </div>
</div>

<?php
/* 加载页面级别资源 */
\common\widgets\images\FileinputAsset::register($this);
?>

<!-- 定义数据块 -->
<?php $this->beginBlock('upload_images_event'); ?>
$(function() {
    $('.fileupload-list').delegate('.fileupload-item','mouseover mouseout',function(e){
        if (e.type == 'mouseover') {
            $(this).find('span').css('display','block');
        } else {
            $(this).find('span').css('display','none');
        }
    });
    $('.fileupload-list').delegate('.fileupload-del','click',function(e){
        $(this).parent().remove();
    });
});
<?php $this->endBlock() ?>
<!-- 将数据块 注入到视图中的某个位置 -->
<?php $this->registerJs($this->blocks['upload_images_event'], \yii\web\View::POS_END); ?>

<!-- 定义数据块 -->
<?php $this->beginBlock('images'); ?>

$(function() {
    /* ======================图集js========================= */
    $(".field-<?=Html::getInputId($model, $attribute)?> .uploadImages<?=$saveDB?>").on("change", function(){
        var fileinput = $(this).parents('.fileinput');
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) return;
        var filesize = files.length;
        var uploadedImg = [];
        var uploadProcess = new Promise(function(resolve,reject){
            var handled = 0
            for(let i in files){
                if (/^image/.test( files[i].type)){
                    var reader = new FileReader();
                    reader.readAsDataURL(files[i]);
                    reader.onloadend = function(){
                        $.ajax({
                            type: 'post',
                            url: '<?=$url?>',
                            data: {imgbase64:this.result,saveDB:<?=$saveDB?>},
                            dataType: 'json',
                            beforeSend: function(){
                                fileinput.find('.fileupload-text').html('上传中...');
                            },
                            success: function(json){
                                if(json.boo){
                                    let url = json.data.url;
                                    <?php if($isShowWH):?>
                                        url = json.data.url + '?w=' + json.data.width + '*h=' + json.data.height
                                    <?php endif;?>
                                    uploadedImg[i] = url;
                                    handled++;
                                    if(handled == filesize){
                                        resolve();
                                    }
                                } else {
                                    reject(json.msg);
                                }
                            },
                            error: function(xhr, type){
                                reject('服务器错误');
                            }
                        });
                    }
                }
            }
        });
    uploadProcess.then(function(){
        var html  = '';
        for(let x in uploadedImg){
            html += '<div class="fileupload-item thumbnail">';
            html += '    <img src="'+uploadedImg[x]+'" />';
            html += '    <span class="fileupload-del">删除</span>';
            html += '    <input type="hidden" name="<?=$field?>[]" value="'+uploadedImg[x]+'" />';
            html += '</div>';
        }
        fileinput.find('.fileupload-list').append(html);
        fileinput.find('.fileupload-text').html('上传成功');
    }).catch(function(err){
        alert(err);
    });


    });
    
});

<?php $this->endBlock() ?>
<!-- 将数据块 注入到视图中的某个位置 -->
<?php $this->registerJs($this->blocks['images'], \yii\web\View::POS_END); ?>