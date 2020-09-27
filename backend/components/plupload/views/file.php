<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2018/9/3
 * Time: 下午12:02
 */
?>
<div class="upload_container__upload_box__bt_container__bt" id="<?= $id?>_container" >
    <div class="upload_container__upload_box__file_container">
        <button id="<?= $id ?>_pickfiles" class="__file_inputs_upload_bt fl" style="margin-left:0;">上传文件</button>
        <div class="clear"></div>
    </div>
    <div class="upload_container__upload_box__file_list" id="<?= $id?>_queueLists">
        <?php foreach ($value as $key => $value):?>
            <div id="" class="__queueLists_temp_item __temp_save_data">
                <i class="file_list__icon"></i>
                <div class="file_list__name">暂存文件<?= $key+1?></div>
                <div class="progress_name"></div>
                <a class="__queueLists_temp_item__delete" data-url="<?= $value?>" href="javascript:void(0)"></a>
            </div>
        <?php endforeach;?>
    </div>
</div>
