<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2019/1/23
 * Time: 9:56 AM
 */
?>
<div class="upload_container__upload_box__bt_container__bt fl" id="<?= $id?>_container">
    <div class="table_father">
        <div class="table_child">
            <div class="__bt_icon">
                <i class="icon create--guide__icon create--guide__icon--upload"></i>
            </div>
            <p class="__bt_text">上传本地图片移动端</p>
            <input id="<?= $id ?>_pickfiles" class="__pickfiles" type="button">
        </div>
    </div>
</div>
<div id="<?= $id ?>_queueLists" class="__queueLists">
    <?php if($showQueue):?>
        <?php foreach ($value as $index => $img):?>
            <div id="<?= $id ?>_<?= $index?>_temp_data" class="__queueLists_temp_item __temp_save_data">
                <img src="<?= $img?>" alt="">
                <div class="__queueLists_temp_item__functions">
                    <p class="filename">暂存文件<?= $index+1?></p>
                    <a class="__queueLists_temp_item__delete" href="javascript:void(0)" data-url="<?= $img?>"></a>
                </div>
            </div>
        <?php endforeach;?>
    <?php endif;?>
</div>
