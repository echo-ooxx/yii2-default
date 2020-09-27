<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2019/5/8
 * Time: 3:58 PM
 */
?>
<div class="" id="<?= $id?>_container">
    <button id="<?= $id ?>_pickfiles" class="__file_inputs_upload_bt"><?= $btText?></button>
</div>
<script id="typeBtAssistDom" type="text/html">
    <div class="common_form_container_big_box_container" id="{fileid}__file_container">
        <div class="common_form_container_big_box_container__cancel" onclick="cancelSingle(this)"></div>
        <div class="common_form_container_group --domRow validate_input_group">
            <div class="common_form_container_group__label font--14 common_form_container_group__children common_form_container_group__label--underline">
                <label for="">单品封面</label>
            </div>
            <div class="common_form_container_group__input_control font--14 common_form_container_group__children --mt20">
                <div class="__input_control clearfix">
                    <div class="__input_control __upload_bts">
                        <div class="__upload_bts__cover __upload_bts__cover_update">
                            <input id="{fileid}__url_input_container" type="hidden" name="single_cover[]" data-conditions="{'required':false,type:'text',error:''}" data-attr="单品封面图">
                            <div class="upload_container__upload_box__bt_container__bt fl --no-hover">
                                <img src="" alt="" id="{fileid}__img_container">
                                <div class="img_upload_bar" id="{fileid}__upload_progress"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="common_form_container_group__hint common_form_container_group__children font--14"></div>
            <div class="common_form_container_group__error common_form_container_group__children font--14"></div>
        </div>
        <div class="common_form_container_group --domRow validate_input_group">
            <div class="common_form_container_group__label font--14 common_form_container_group__children">
                <label for="">单品标题</label>
            </div>
            <div class="common_form_container_group__input_control font--14 common_form_container_group__children">
                <div class="__input_control clearfix">
                    <div class="__input_control __input_control--longest">
                        <input value="" type="text" name="single_title[]" data-conditions="{'required':false,type:'text',error:''}" data-attr="单品标题">
                    </div>
                </div>
            </div>
            <div class="common_form_container_group__hint common_form_container_group__children font--14"></div>
            <div class="common_form_container_group__error common_form_container_group__children font--14"></div>
        </div>
        <div class="common_form_container_group --domRow validate_input_group">
            <div class="common_form_container_group__label font--14 common_form_container_group__children">
                <label for="">单品链接</label>
                <span class="common_form_container_group__label__hint">请输入带有http或https的标准链接</span>
            </div>
            <div class="common_form_container_group__input_control font--14 common_form_container_group__children">
                <div class="__input_control clearfix">
                    <div class="__input_control __input_control--longest">
                        <input value="" type="text" name="single_link[]" data-conditions="{'required':false,type:'text',error:''}" data-attr="单品链接">
                    </div>
                </div>
            </div>
            <div class="common_form_container_group__hint common_form_container_group__children font--14"></div>
            <div class="common_form_container_group__error common_form_container_group__children font--14"></div>
        </div>
    </div>
</script>