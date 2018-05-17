function hightlightnav(url){
    var $nav_accordion = $('#nav-accordion');
    var ele = $nav_accordion.find('a[nav="'+url+'"]');
    ele.closest('li').addClass('active');
    ele.closest('li').parent().css('display','block');
    ele.closest('li').parent().prev().addClass('active');
}