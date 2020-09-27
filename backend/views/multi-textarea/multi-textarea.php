<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2019/12/25
 * Time: 4:33 PM
 */

\backend\assets\MultiTextareaAsset::register($this);
?>
<div>
    <div id="created_container" class="created_container">
        <div class="container--1380">
            <div class="create_container__main_body">
                <div class="create_container__right_body" style="background-color: #f9f9f9;">
                    <div class="create_container__right__main_body" data-isscroll="<?= $isScroll ? 1 : 0?>">
                        <!--guide-->
                        <div class="create__guide_container<?= $works_content ? ' --hide' : '';?>" id="create__guide_container">
                            <div class="create__guide_container__item" onclick="firstAddImg('common')">
                                <div class="create__guide_container__item__icon">
                                    <i class="icon create--guide__icon create--guide__icon--upload"></i>
                                </div>
                                <p class="font--15 create__guide_container__item__text">上传单独素材</p>
                            </div>
                            <?php if($isScroll):?>
                                <div class="create__guide_container__item" onclick="firstAddImg('scroll')">
                                    <div class="create__guide_container__item__icon">
                                        <i class="icon create--guide__icon create--guide__icon--upload"></i>
                                    </div>
                                    <p class="font--15 create__guide_container__item__text">上传轮播</p>
                                </div>
                            <?php endif;?>
                            <div class="create__guide_container__item" onclick="firstAddText()">
                                <div class="create__guide_container__item__icon">
                                    <i class="icon create--guide__icon create--guide__icon--text"></i>
                                </div>
                                <p class="font--15 create__guide_container__item__text">插入文本</p>
                            </div>
                        </div>
                        <!--insert-->
                        <div style="padding-top: 80px;" class="insert_box_components add_html_layout_container<?= $works_content ? '' : ' --hide';?>" id="insert_box_components">
                            <div class="insert_box_components__insert">
                                <div class="insert_box_components__insert__sign">
                                    <div class="__sign line"></div>
                                    <div class="__sign row"></div>
                                    <div class="__sign line"></div>
                                </div>
                            </div>
                            <div class="insert_box_components__insert_bts">
                                <div class="insert_box_components__insert_bts__item">
                                    <p>插入内容：</p>
                                </div>
                                <div class="insert_box_components__insert_bts__item insert_box_components__insert_bts__item--add" onclick="addImg(this,'common')">
                                    <i class="icon create__icon--small create__icon--small--upload"></i>
                                    <p>新增单独素材</p>
                                </div>
                                <?php if($isScroll):?>
                                    <div class="insert_box_components__insert_bts__item insert_box_components__insert_bts__item--add" onclick="addImg(this,'scroll')">
                                        <i class="icon create__icon--small create__icon--small--upload"></i>
                                        <p>新增轮播</p>
                                    </div>
                                <?php endif;?>
                                <div class="insert_box_components__insert_bts__item insert_box_components__insert_bts__item--add" onclick="addTextarea(this)">
                                    <i class="icon create__icon--small create__icon--small--text"></i>
                                    <p>新增文字</p>
                                </div>
                            </div>
                        </div>
                        <?php if($works_content && is_array($works_content)):?>
                            <?php foreach ($works_content as $key => $value):?>
                                <?php
                                $isText = $value['type'] == 'textarea';
                                $isVideo = $value['type'] == 'video';
                                $isImg = $value['type'] == 'img';
                                $isScrollImg = $value['type'] == 'scroll';
                                $isAassemblyImg = $value['type'] == 'assembly';
                                ?>
                                <?php if($isText):?>
                                    <div class="add_html_layout_container add_html_sign" style="">
                                        <div class="add_html_layout_container__add_box" style="">
                                            <div class="__add_box_main_body" style="">
                                                <div class="__add_box_main_body__action_html" style="">
                                                    <div class="__action_html__text_layout" style="">
                                                        <div class="text_temp"><?= $value['value']?></div>
                                                    </div>
                                                    <div class="__add_box_main_body__sort_bts">
                                                        <div class="__add_box_main_body__sort_bts__main_body">
                                                            <div class="__add_box_main_body__sort_bts__main_body__item __add_box_main_body__sort_bts__main_body__item--sort" onclick="content_sort()" title="排序"></div>
                                                            <div class="__add_box_main_body__sort_bts__main_body__item __add_box_main_body__sort_bts__main_body__item--editText" onclick="reedit(this)" title="编辑"></div>
                                                            <div class="__add_box_main_body__sort_bts__main_body__item __add_box_main_body__sort_bts__main_body__item--delete" onclick="delEdit(this)" title="删除"></div>
                                                            <div class="__add_box_main_body__sort_bts__main_body__item __add_box_main_body__sort_bts__main_body__item--submit" onclick="makeSure(this)" title="确定"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="insert_box_components">
                                            <div class="insert_box_components__insert">
                                                <div class="insert_box_components__insert__sign">
                                                    <div class="__sign line"></div>
                                                    <div class="__sign row"></div>
                                                    <div class="__sign line"></div>
                                                </div>
                                            </div>
                                            <div class="insert_box_components__insert_bts">
                                                <div class="insert_box_components__insert_bts__item">
                                                    <p>插入内容：</p>
                                                </div>
                                                <div class="insert_box_components__insert_bts__item insert_box_components__insert_bts__item--add" onclick="addImg(this,'common')">
                                                    <i class="icon create__icon--small create__icon--small--upload"></i>
                                                    <p>新增单独素材</p>
                                                </div>
                                                <?php if($isScroll):?>
                                                    <div class="insert_box_components__insert_bts__item insert_box_components__insert_bts__item--add" onclick="addImg(this,'scroll')">
                                                        <i class="icon create__icon--small create__icon--small--upload"></i>
                                                        <p>新增轮播</p>
                                                    </div>
                                                <?php endif;?>
                                                <div class="insert_box_components__insert_bts__item insert_box_components__insert_bts__item--add" onclick="addTextarea(this)">
                                                    <i class="icon create__icon--small create__icon--small--text"></i>
                                                    <p>新增文字</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php elseif($isVideo):?>
                                    <div class="add_html_layout_container add_html_sign">
                                        <div class="add_html_layout_container__add_box">
                                            <div class="__add_box_main_body">
                                                <div class="__add_box_main_body__action_html">
                                                    <div class="__action_html__video_layout">
                                                        <video data-cover="<?= array_key_exists('cover',$value) ? $value['cover'] : ''?>" src="<?= $value['value']?>"></video>
                                                        <?php if(array_key_exists('cover',$value)):?>
                                                            <p class="video-cover"><img src="<?= array_key_exists('cover',$value) ? $value['cover'] : ''?>" alt=""></p>
                                                        <?php endif;?>
                                                    </div>
                                                    <div class="__add_box_main_body__sort_bts">
                                                        <div class="__add_box_main_body__sort_bts__main_body">
                                                            <div class="__add_box_main_body__sort_bts__main_body__item __add_box_main_body__sort_bts__main_body__item--sort" onclick="content_sort()" title="排序"></div>
                                                            <div class="__add_box_main_body__sort_bts__main_body__item __add_box_main_body__sort_bts__main_body__item--editUpload" onclick="reupload(this)" title="编辑"></div>
                                                            <div class="__add_box_main_body__sort_bts__main_body__item __add_box_main_body__sort_bts__main_body__item--editCover" onclick="uploadCover(this)" title="编辑"></div>
                                                            <div class="__add_box_main_body__sort_bts__main_body__item __add_box_main_body__sort_bts__main_body__item--delete" onclick="delEdit(this)" title="删除"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="insert_box_components">
                                            <div class="insert_box_components__insert">
                                                <div class="insert_box_components__insert__sign">
                                                    <div class="__sign line"></div>
                                                    <div class="__sign row"></div>
                                                    <div class="__sign line"></div>
                                                </div>
                                            </div>
                                            <div class="insert_box_components__insert_bts">
                                                <div class="insert_box_components__insert_bts__item">
                                                    <p>插入内容：</p>
                                                </div>
                                                <div class="insert_box_components__insert_bts__item insert_box_components__insert_bts__item--add" onclick="addImg(this,'common')">
                                                    <i class="icon create__icon--small create__icon--small--upload"></i>
                                                    <p>新增单个素材</p>
                                                </div>
                                                <?php if($isScroll):?>
                                                    <div class="insert_box_components__insert_bts__item insert_box_components__insert_bts__item--add" onclick="addImg(this,'scroll')">
                                                        <i class="icon create__icon--small create__icon--small--upload"></i>
                                                        <p>新增轮播</p>
                                                    </div>
                                                <?php endif;?>
                                                <div class="insert_box_components__insert_bts__item insert_box_components__insert_bts__item--add" onclick="addTextarea(this)">
                                                    <i class="icon create__icon--small create__icon--small--text"></i>
                                                    <p>新增文字</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php elseif($isImg):?>
                                    <div class="add_html_layout_container add_html_sign">
                                        <div class="add_html_layout_container__add_box">
                                            <div class="__add_box_main_body">
                                                <div class="__add_box_main_body__action_html">
                                                    <div class="__action_html__img_layout">
                                                        <img src="<?= $value['value']?>" data-type="img" data-comment="<?= $value['comment']?>" />
                                                        <span class="imgComment"><?= $value['comment']?></span>
                                                    </div>
                                                    <div class="__add_box_main_body__sort_bts">
                                                        <div class="__add_box_main_body__sort_bts__main_body">
                                                            <div class="__add_box_main_body__sort_bts__main_body__item __add_box_main_body__sort_bts__main_body__item--sort" onclick="content_sort()" title="排序"></div>
                                                            <div class="__add_box_main_body__sort_bts__main_body__item __add_box_main_body__sort_bts__main_body__item--editUpload" onclick="reupload(this)" title="编辑"></div>
                                                            <div class="__add_box_main_body__sort_bts__main_body__item __add_box_main_body__sort_bts__main_body__item--editComment" onclick="editComment(this)" title="备注"></div>
                                                            <div class="__add_box_main_body__sort_bts__main_body__item __add_box_main_body__sort_bts__main_body__item--delete" onclick="delEdit(this)" title="删除"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="insert_box_components">
                                            <div class="insert_box_components__insert">
                                                <div class="insert_box_components__insert__sign">
                                                    <div class="__sign line"></div>
                                                    <div class="__sign row"></div>
                                                    <div class="__sign line"></div>
                                                </div>
                                            </div>
                                            <div class="insert_box_components__insert_bts">
                                                <div class="insert_box_components__insert_bts__item">
                                                    <p>插入内容：</p>
                                                </div>
                                                <div class="insert_box_components__insert_bts__item insert_box_components__insert_bts__item--add" onclick="addImg(this,'common')">
                                                    <i class="icon create__icon--small create__icon--small--upload"></i>
                                                    <p>新增单个素材</p>
                                                </div>
                                                <?php if($isScroll):?>
                                                    <div class="insert_box_components__insert_bts__item insert_box_components__insert_bts__item--add" onclick="addImg(this,'scroll')">
                                                        <i class="icon create__icon--small create__icon--small--upload"></i>
                                                        <p>新增轮播</p>
                                                    </div>
                                                <?php endif;?>
                                                <div class="insert_box_components__insert_bts__item insert_box_components__insert_bts__item--add" onclick="addTextarea(this)">
                                                    <i class="icon create__icon--small create__icon--small--text"></i>
                                                    <p>新增文字</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php else:?>
                                    <div class="add_html_layout_container add_html_sign">
                                        <div class="add_html_layout_container__add_box">
                                            <div class="__add_box_main_body">
                                                <div class="__add_box_main_body__action_html" data-comment="<?= $value['comment']?>">
                                                    <div class="__action_html__scroll_layout">
                                                        <?php
                                                        if($isScrollImg){
                                                            $className = 'scroll_img';
                                                        }elseif ($isAassemblyImg){
                                                            $className = 'assembly_img';
                                                        }
                                                        ?>
                                                        <?php foreach ($value['value'] as $key => $scroll):?>
                                                            <div data-src="<?= $scroll?>" class="<?= $className?>" style="background-image:url('<?= $scroll?>')" alt="" data-type="img"></div>
                                                        <?php endforeach;?>
                                                    </div>
                                                    <div class="__add_box_main_body__sort_bts">
                                                        <div class="__add_box_main_body__sort_bts__main_body">
                                                            <div class="__add_box_main_body__sort_bts__main_body__item __add_box_main_body__sort_bts__main_body__item--sort" onclick="content_sort()" title="排序"></div>
                                                            <?php if($isAassemblyImg):?>
                                                                <div class="__add_box_main_body__sort_bts__main_body__item __add_box_main_body__sort_bts__main_body__item--editComment" onclick="editComment(this)" title="备注"></div>
                                                            <?php endif;?>
                                                            <div class="__add_box_main_body__sort_bts__main_body__item __add_box_main_body__sort_bts__main_body__item--delete" onclick="delEdit(this)" title="删除"></div>
                                                        </div>
                                                    </div>
                                                    <?php if($value['comment'] > 0):?>
                                                        <p class="dataComment">当前每行显示：<?= $value['comment']?>个小图标</p>
                                                    <?php endif;?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="insert_box_components">
                                            <div class="insert_box_components__insert">
                                                <div class="insert_box_components__insert__sign">
                                                    <div class="__sign line"></div>
                                                    <div class="__sign row"></div>
                                                    <div class="__sign line"></div>
                                                </div>
                                            </div>
                                            <div class="insert_box_components__insert_bts">
                                                <div class="insert_box_components__insert_bts__item">
                                                    <p>插入内容：</p>
                                                </div>
                                                <div class="insert_box_components__insert_bts__item insert_box_components__insert_bts__item--add" onclick="addImg(this,'common')">
                                                    <i class="icon create__icon--small create__icon--small--upload"></i>
                                                    <p>新增单独素材</p>
                                                </div>
                                                <?php if($isScroll):?>
                                                    <div class="insert_box_components__insert_bts__item insert_box_components__insert_bts__item--add" onclick="addImg(this,'scroll')">
                                                        <i class="icon create__icon--small create__icon--small--upload"></i>
                                                        <p>新增轮播</p>
                                                    </div>
                                                <?php endif;?>
                                                <div class="insert_box_components__insert_bts__item insert_box_components__insert_bts__item--add" onclick="addTextarea(this)">
                                                    <i class="icon create__icon--small create__icon--small--text"></i>
                                                    <p>新增文字</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif;?>
                            <?php endforeach;?>
                        <?php endif;?>
                    </div>
                    <div class="create_container__right_body__bts">
                        <div class="__bt_box font--16" onclick="next_step(this)" data-posturl="<?= $postUrl?>" data-status="<?= $statusNormal?>">发布</div>
                        <div style="background-color: #41cac0;" class="__bt_box font--16" onclick="next_step(this)" data-posturl="<?= $postUrl?>" data-status="<?= $statusDraft?>">草稿箱</div>
                        <div style="background-color: #A8D76F;" class="__bt_box font--16">
                            <a href="<?= $backurl?>">返回</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--弹窗-->
    <div class="upload_container" id="upload_container">
        <div class="upload_container__upload_box">
            <div class="upload_container__upload_box__close" onclick="closeAlert()"></div>
            <div class="upload_container__upload_box__title">
                <p>上传素材</p>
            </div>
            <div class="upload_container__upload_box__hint">
                <p><i class="icon"></i>多媒体文件支持jpg/gif/png格式，单文件不超过20M，支持批量上传，最多10张。</p>
            </div>
            <div class="upload_container__upload_box__bt_container">
                <?= \backend\components\plupload\PluploadWidget::widget([
                    'uploadto' => '/site/uploadimage?uploadType=file',
                    'fileSizeLimit' => '40480k',
                    'fileNumLimit' => 50,
                    'fileExtLimit' => 'jpg,jpeg,png,gif,mp4',
                    'showQueue' => false,
                    'showCommonQueue' => true
                ])?>
            </div>
        </div>
    </div>
    <!--排序-->
    <div class="sort_container" id="sort_container">
        <div class="container--1200">
            <div class="sort_container__main_body">
                <div class="sort_container__main_body__title">
                    <p class="font--20">编辑模块顺序</p>
                </div>
                <div class="sort_container__main_body__dad_container dad-active dad-container" id="sort_container__main_body__dad_container"></div>
                <div class="sort_container__main_body__bt_groups">
                    <div class="__page_bts __page_bts--save" onclick="saveSort()">保存</div>
                    <div class="__page_bts __page_bts--cancel" onclick="cancelSort()">取消</div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .__action_html__video_layout{
        position: relative;
    }
    .__action_html__video_layout .video-cover{
        position: absolute;
        top: 0;
        left: 0;
        border: 1px solid #000;
        width: 300px;
        height: 300px;
        overflow: hidden;
        background-color: #000;
    }
    .__action_html__video_layout .video-cover img{
        width: 100%;
        position: absolute;
        top: 0;
        left: 0;
    }
</style>