/**
 * 详情页js。
 *
 * @author    fairyin <fairyin@126.cn>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

$(document).ready(function(){
    var deleteClick = function(){
        Swal({
            title: '确定要删除吗？',
            text: "",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '删除',
            cancelButtonText: '取消'
        }).then((result) => {
            if (result.value) {
                var url = '/photo/del';
                var photo_id = $(".lg-sub-html").html();
                _common.ajax(url, 'POST', {
                    pid: photo_id
                }, function(data){
                }, function(error){
                });
            }
        });
    };

    var $lg = $('#lightgallery');

    $lg.lightGallery(
        {
            share: false,
            download: false,
            controls: true,
            exThumbImage: false,
            thumbnail: true,
            showThumbByDefault: false,
            animateThumb: true,
            currentPagerPosition: 'middle',
            thumbWidth: 100,
            thumbMargin: 5,
        }
    );

    $lg.on('onBeforeOpen.lg', function(event){
        $("body").addClass('noscroll');
    });

    $lg.on('onBeforeClose.lg', function(event){
        $("body").removeClass('noscroll');
    });

    $lg.on('onAfterOpen.lg',function(event){
        $(".lg-toolbar").append('<span class="lg-delete lg-icon"></span>');
        $(".lg-toolbar .lg-delete.lg-icon").on('click', deleteClick);
    });

    $("img.lazyload").lazyload();

    $(".tz").on('click', function(){
        window.location.href = $(this).attr('data-href');
    });
});
