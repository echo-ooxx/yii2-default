(function(window){
    'use strict';

    function isEmptyObject(obj){
        for(var key in obj){
            return false;
        }
        return true;
    }

    var Qupload = function(opts){

        if(opts === undefined){
            opts = {};
        }

        this.$body = $('body');

        this.$alert = opts.alert || $('#upload_container');

        this.textarea = opts.textarea || 'textarea';

        var defaultTextareaConfig = {
            //这里可以选择自己需要的工具按钮名称,此处仅选择如下五个
            toolbars: [['source', 'horizontal', 'removeformat', 'formatmatch', '|', 'fontsize', 'bold', 'italic', 'underline', 'forecolor', '|', 'justifyleft', 'justifycenter', 'justifyright', '|', 'indent', '|', 'insertorderedlist', 'lineheight', '|', 'link', 'emotion']],
            //关闭elementPath
            elementPathEnabled: false,
            //关闭自身统计
            wordCount: false,
            // initialFrameWidth:'100',
            scaleEnabled: false,
            autoHeightEnabled: false
            //默认的编辑区域高度
            // initialFrameHeight: 300
            //更多其他参数，请参考ueditor.config.js中的配置项
        };

        this.textareaConfig = opts.textareaConfig || defaultTextareaConfig;

        this.addFatherContainerClass = 'add_html_layout_container';

        this.$addMainContainer = $('.create_container__right__main_body');

        this.$insertBoxComponents = $('.insert_box_components');

        this.$elem = undefined;

        this.$sortContainer = opts.sortContainer || $('#sort_container');
        this.$sortAddContainer = opts.sortAddContainer || $('#sort_container__main_body__dad_container');

        this.$imgAfterElem = undefined;

        this.isScroll = this.$addMainContainer.data('isscroll') == 1 ? true : false;

        this.uploadFiles = {};

        this.$beforeDom = undefined;

        this.uploadType = 'add';

        this.uploadFileType = 'common';

        let _temp_layout_without_scroll = '<div class="add_html_layout_container add_html_sign">' +
            '                        <div class="add_html_layout_container__add_box">' +
            '                            <div class="__add_box_main_body">' +
            '                                <div class="__add_box_main_body__action_html">' +
            '                                   {content}'+
            '                                </div>' +
            '                            </div>' +
            '                        </div>' +
            '                        <div class="insert_box_components">' +
            '                            <div class="insert_box_components__insert">' +
            '                                <div class="insert_box_components__insert__sign">' +
            '                                    <div class="__sign line"></div>' +
            '                                    <div class="__sign row"></div>' +
            '                                    <div class="__sign line"></div>' +
            '                                </div>' +
            '                            </div>' +
            '                            <div class="insert_box_components__insert_bts">' +
            '                                <div class="insert_box_components__insert_bts__item">' +
            '                                    <p>插入内容：</p>' +
            '                                </div>' +
            '                                <div class="insert_box_components__insert_bts__item insert_box_components__insert_bts__item--add" onclick="addImg(this,\'common\')">' +
            '                                    <i class="icon create__icon--small create__icon--small--upload"></i>' +
            '                                    <p>新增单独素材</p>' +
            '                                </div>' +
            '                                <div class="insert_box_components__insert_bts__item insert_box_components__insert_bts__item--add" onclick="addTextarea(this)">' +
            '                                    <i class="icon create__icon--small create__icon--small--text"></i>' +
            '                                    <p>新增文字</p>' +
            '                                </div>' +
            '                            </div>' +
            '                        </div>' +
            '                    </div>';

        let _temp_layout_with_scroll = '<div class="add_html_layout_container add_html_sign">' +
            '                        <div class="add_html_layout_container__add_box">' +
            '                            <div class="__add_box_main_body">' +
            '                                <div class="__add_box_main_body__action_html">' +
            '                                   {content}'+
            '                                </div>' +
            '                            </div>' +
            '                        </div>' +
            '                        <div class="insert_box_components">' +
            '                            <div class="insert_box_components__insert">' +
            '                                <div class="insert_box_components__insert__sign">' +
            '                                    <div class="__sign line"></div>' +
            '                                    <div class="__sign row"></div>' +
            '                                    <div class="__sign line"></div>' +
            '                                </div>' +
            '                            </div>' +
            '                            <div class="insert_box_components__insert_bts">' +
            '                                <div class="insert_box_components__insert_bts__item">' +
            '                                    <p>插入内容：</p>' +
            '                                </div>' +
            '                                <div class="insert_box_components__insert_bts__item insert_box_components__insert_bts__item--add" onclick="addImg(this,\'common\')">' +
            '                                    <i class="icon create__icon--small create__icon--small--upload"></i>' +
            '                                    <p>新增单独素材</p>' +
            '                                </div>' +
            '                                <div class="insert_box_components__insert_bts__item insert_box_components__insert_bts__item--add" onclick="addImg(this,\'scroll\')">' +
            '                                    <i class="icon create__icon--small create__icon--small--upload"></i>' +
            '                                    <p>新增轮播</p>' +
            '                                </div>' +
            '                                <div class="insert_box_components__insert_bts__item insert_box_components__insert_bts__item--add" onclick="addTextarea(this)">' +
            '                                    <i class="icon create__icon--small create__icon--small--text"></i>' +
            '                                    <p>新增文字</p>' +
            '                                </div>' +
            '                            </div>' +
            '                        </div>' +
            '                    </div>';

        this.addLayout = this.isScroll ? _temp_layout_with_scroll : _temp_layout_without_scroll;



        this.textLayout = '<div class="__action_html__text_layout">' +
            '       <textarea id="textarea" style="width:100%;height:200px;"></textarea>' +
            '   </div>' +
            '   <div class="__add_box_main_body__sort_bts">' +
            '       <div class="__add_box_main_body__sort_bts__main_body">' +
            '           <div class="__add_box_main_body__sort_bts__main_body__item __add_box_main_body__sort_bts__main_body__item--sort" onclick="content_sort()" title="排序"></div>' +
            '           <div class="__add_box_main_body__sort_bts__main_body__item __add_box_main_body__sort_bts__main_body__item--editText" onclick="reedit(this)" title="编辑"></div>' +
            '           <div class="__add_box_main_body__sort_bts__main_body__item __add_box_main_body__sort_bts__main_body__item--delete" onclick="delEdit(this)" title="删除"></div>' +
            '           <div class="__add_box_main_body__sort_bts__main_body__item __add_box_main_body__sort_bts__main_body__item--submit" onclick="makeSure(this)" title="确定"></div>' +
            '       </div>' +
            '   </div>';
        this.imgLayout = '<div class="__action_html__img_layout">' +
            '       <img src="{src}" alt="" data-type="{type}">' +
            '          <span class="imgComment"></span>' +
            '   </div>' +
            '       <div class="__add_box_main_body__sort_bts">' +
            '           <div class="__add_box_main_body__sort_bts__main_body">' +
            '           <div class="__add_box_main_body__sort_bts__main_body__item __add_box_main_body__sort_bts__main_body__item--sort" onclick="content_sort()" title="排序"></div>' +
            '           <div class="__add_box_main_body__sort_bts__main_body__item __add_box_main_body__sort_bts__main_body__item--editUpload" onclick="reupload(this)" title="编辑"></div>' +
            '           <div class="__add_box_main_body__sort_bts__main_body__item __add_box_main_body__sort_bts__main_body__item--editComment" onclick="editComment(this)" title="备注"></div>' +
            '           <div class="__add_box_main_body__sort_bts__main_body__item __add_box_main_body__sort_bts__main_body__item--delete" onclick="delEdit(this)" title="删除"></div>' +
            '       </div>' +
            '   </div>';
        this.scrollLayout = '<div class="__action_html__scroll_layout">' +
            '       {scroll}' +
            '   </div>' +
            '       <div class="__add_box_main_body__sort_bts">' +
            '           <div class="__add_box_main_body__sort_bts__main_body">' +
            '           <div class="__add_box_main_body__sort_bts__main_body__item __add_box_main_body__sort_bts__main_body__item--sort" onclick="content_sort()" title="排序"></div>' +
            '           <div class="__add_box_main_body__sort_bts__main_body__item __add_box_main_body__sort_bts__main_body__item--editComment" onclick="editComment(this)" title="备注"></div>' +
            '           <div class="__add_box_main_body__sort_bts__main_body__item __add_box_main_body__sort_bts__main_body__item--delete" onclick="delEdit(this)" title="删除"></div>' +
            '       </div>' +
            '   </div>';
        this.videoLayout = '<div class="__action_html__video_layout">' +
            '       <video src="{src}"></video>' +
            '   </div>' +
            '       <div class="__add_box_main_body__sort_bts">' +
            '           <div class="__add_box_main_body__sort_bts__main_body">' +
            '           <div class="__add_box_main_body__sort_bts__main_body__item __add_box_main_body__sort_bts__main_body__item--sort" onclick="content_sort()" title="排序"></div>' +
            '           <div class="__add_box_main_body__sort_bts__main_body__item __add_box_main_body__sort_bts__main_body__item--editUpload" onclick="reupload(this)" title="编辑"></div>' +
            '           <div class="__add_box_main_body__sort_bts__main_body__item __add_box_main_body__sort_bts__main_body__item--editCover" onclick="uploadCover(this)" title="编辑"></div>' +
            '           <div class="__add_box_main_body__sort_bts__main_body__item __add_box_main_body__sort_bts__main_body__item--delete" onclick="delEdit(this)" title="删除"></div>' +
            '       </div>' +
            '   </div>';
        this.sortLayout = '<div class="sort_container__main_body__dad_container__item dads-children dad-draggable-area" data-dad-id="1" data-order="{orderindex}">{content}<div class="sort_container__main_body__dad_container__item__remove_bt" onclick="removeInSort(this)"></div></div>';
        this.sortTextLayout = '<div class="dadabel_area text_layout"><p>{text}</p></div>';
        this.sortImgLayout = '<div class="dadabel_area"><img src="{src}" /></div>';
        this.sortScrollLayout = '<div class="dadabel_area"><img src="{src}" /></div>';
        this.sortVideoLayout = '<div class="dadabel_area"><video src="{src}"></video></div>';
        this.sortCloneData = '';

        //初始化editro
        // this.initEditor();
        // var _this = this;
        // $('body').on('click',function(){
        //     if(_this.ue){
        //         _this._ueditorDestory();
        //     }
        // });
    };

    Qupload.prototype = {
        constructor: Qupload,
        initEditor:function(){
            this.ue = UE.getEditor(this.textarea,this.textareaConfig);
        },
        openAlert: function(){
            this.$alert.fadeIn(100);
        },
        closeAlert: function(){
            this.$alert.fadeOut(100);
        },
        addTextarea: function(elem){
            event.stopPropagation();
            var $create__guide_container = $('#create__guide_container'),
                $insert_box_components = $('#insert_box_components');
            if(!$create__guide_container.hasClass('--hide')){
                $create__guide_container.addClass('--hide');
                $insert_box_components.removeClass('--hide');
            }

            this._ueditorDestory();
            this.$elem = elem;
            var source = this.addLayout.replace('{content}',this.textLayout);
            if(this.$elem == undefined){
                this.$addMainContainer.append(source);
            }else{
                if(this.$beforeDom != undefined && this.$beforeDom.length > 0){
                    this.$beforeDom.after(source);
                }else{
                    var $thatFather = $(this.$elem).parents('.'+this.addFatherContainerClass);
                    $thatFather.after(source);
                }
                this.$beforeDom = undefined;
            }
            this.initEditor();
        },
        reedit:function(elem){
            this._ueditorDestory();
            var $elem = $(elem);
            var $father = $elem.parents('.' + this.addFatherContainerClass);
            var $text_temp = $father.find('.text_temp');
            if($text_temp.length > 0){
                var text = $text_temp.html();
                $text_temp.replaceWith('<textarea id="textarea" style="width:100%;height:200px;">'+text+'</textarea>');
                this.initEditor();
            }
        },
        _ueditorDestory:function(){
            if(this.ue != undefined){
                //如果存在ue，把现在的结果存下来
                var text = this.ue.getContent();
                if($.trim(text) == ''){
                    this.ue.destroy();
                    var $nowFather = $('#' + this.textarea).parents('.' + this.addFatherContainerClass);
                    this.$beforeDom = $nowFather.prev('.' + this.addFatherContainerClass);
                    $nowFather.remove();
                }else{
                    this.ue.destroy();
                    //把整个ueditor替换掉
                    $('#' + this.textarea).replaceWith('<div class="text_temp">'+text+'</div>');
                }
                this.ue = undefined;
            }
        },
        addImg: function(){
            this._ueditorDestory();
            var html = '';
            if(!isEmptyObject(this.uploadFiles)){
                //有数据
                var $create__guide_container = $('#create__guide_container'),
                    $insert_box_components = $('#insert_box_components');
                if(!$create__guide_container.hasClass('--hide')){
                    $create__guide_container.addClass('--hide');
                    $insert_box_components.removeClass('--hide');
                }

                var one_url = '';
                var scroll_imgs = '';
                for (var key in this.uploadFiles){
                    var file = this.uploadFiles[key];
                    var fileId = file.id;
                    var src = file.url;
                    var type = file.type || 'img';
                    switch (type){
                        case 'img':
                        case 'pano':
                            var img = this.imgLayout.replace('{src}',src);
                            img = img.replace('{type}',type);
                            break;
                        case 'video':
                            var img = this.videoLayout.replace('{src}',src);
                            break;
                    }

                    if(this.uploadFileType == 'common'){
                        var source = this.addLayout.replace('{content}',img);
                        html += source;
                        one_url = src;
                    }else if(this.uploadFileType == 'scroll' || this.uploadFileType == 'assembly'){
                        let className = this.uploadFileType == 'scroll' ? 'scroll_img' : 'assembly_img';
                        scroll_imgs += '<div data-src="'+src+'" class="'+className+'" style="background-image:url('+src+')" alt="" data-type="'+type+'"></div>';
                    }
                }
                if(this.uploadFileType == 'scroll' || this.uploadFileType == 'assembly'){
                    let scroll_imgs_html = this.scrollLayout.replace('{scroll}',scroll_imgs);
                    var source = this.addLayout.replace('{content}',scroll_imgs_html);
                    html += source;
                }
                switch (this.uploadType){
                    case 'add':
                        if(this.$imgAfterElem != undefined){
                            var $thatFather = $(this.$imgAfterElem).parents('.' + this.addFatherContainerClass);
                            $thatFather.after(html);
                            this.$imgAfterElem = undefined;
                        }else{
                            this.$addMainContainer.append(html);
                        }
                        break;
                    case 'edit':
                        var $thatFather = $(this.$imgAfterElem).parents('.' + this.addFatherContainerClass);
                        $thatFather.find('.__add_box_main_body__action_html').html(img);
                        // $thatFather.find('.__action_html__img_layout img').attr('src',one_url);
                        break;
                    case 'cover':
                        var $thatFather = $(this.$imgAfterElem).parents('.' + this.addFatherContainerClass);
                        var $obj = $thatFather.find('.__action_html__video_layout');
                        $obj.find('.video-cover').remove();
                        const coverHtml = `<p class="video-cover"><img src="${src}" /></p>`;
                        setTimeout(function(){
                            $obj.append(coverHtml);
                        },100);
                        $obj.children('video').data('cover',src);
                        break;
                    default:
                        break;
                }

                this.uploadFiles = {};
            }
            //没有数据
            this.closeAlert();
        },
        reupload: function(){

        },
        editComment: function(){},
        delEdit:function(elem){
            var $elem = $(elem);
            $elem.parents('.' + this.addFatherContainerClass).remove();
        },
        makeSure: function(elem){
            this._ueditorDestory();
        },
        contentSort: function(){
            var _this = this;
            _this._ueditorDestory();
            var $sort_container = _this.$sortContainer;
            var $add_html_sign = $('.create_container__right__main_body .add_html_sign');
            this.sortCloneData = $add_html_sign;
            var $sort_container__main_body__dad_container = _this.$sortAddContainer;
            $sort_container__main_body__dad_container.html('');
            var source = '';
            var html = '';
            if($add_html_sign.length > 0){

                $add_html_sign.each(function(key,ele){
                    var $ele = $(this);
                    var sortLayout = _this.sortLayout.replace('{orderindex}',key);
                    if($ele.find('.__action_html__img_layout').length > 0){
                        //图片
                        var img = $ele.find('img').attr('src');
                        source = _this.sortImgLayout.replace('{src}',img);
                    }else if($ele.find('.__action_html__text_layout').length > 0){
                        //文字
                        var text = $ele.find('.__action_html__text_layout .text_temp').text();
                        if (text.length > 31) {
                            text = text.substring(0, 31) + "...";
                        }
                        source = _this.sortTextLayout.replace('{text}',text);
                    }else if($ele.find('.__action_html__video_layout').length > 0){
                        //视频
                        var img = $ele.find('video').attr('src');
                        source = _this.sortVideoLayout.replace('{src}',img);
                    }else if($ele.find('.__action_html__scroll_layout').length > 0){
                        var img = $ele.find('.scroll_img').first().data('src') || $ele.find('.assembly_img').first().data('src');
                        source = _this.sortImgLayout.replace('{src}',img);
                    }
                    source = sortLayout.replace('{content}',source);
                    html += source;
                });
                $sort_container__main_body__dad_container.append(html);
            }
            _this.openSort();

        },
        openSort:function(){
            var _this = this;
            _this.$sortContainer.fadeIn(100,function(){
                _this.$sortAddContainer.dad({
                    draggable:'div.dadabel_area'
                });
            });
        },
        saveSort:function(){
            var _this = this;
            var $sorted_index = _this.$sortAddContainer.find('.dads-children');
            _this.$addMainContainer.find('.add_html_sign').remove();
            $sorted_index.each(function(i,ele){
                var queueByIndex = $(ele).data('order');
                _this.$addMainContainer.append(_this.sortCloneData[queueByIndex]);
            });
            _this.cancelSort();
        },
        cancelSort:function(){
            var _this = this;
            _this.$sortContainer.fadeOut(100,function(){
                _this.sortCloneData = '';
            });
        }
    };

    window.Qupload = Qupload

}(window));