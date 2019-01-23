/**
 * 专辑列表js。
 *
 * @author    fairyin <fairyin@126.cn>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

String.prototype.format = function () {
    var args = arguments;
    var reg = /\{(\d+)\}/g;
    return this.replace(reg, function (g0, g1) {
        return args[+g1];
    });
};

$(function(){
    if ("object" !== typeof _album) _album = {};

    _album.page = 1;

    _album.dropid = 'dropload';

    _album.dropdelid = '';

    _album.initDropLoad = function(){
        _album.dropload = $('#' + _album.dropid).dropload({
            scrollArea: window,
            distance: 500,
            threshold: 1500,
            loadDownFn: function(me){
                var result = '',
                year = $(".dropdown-menu.dropdown-menu-right.dp-year").find(".dropdown-item.active").attr('data-year'),
                model = $(".dropdown-menu.dropdown-menu-right.dp-model").find(".dropdown-item.active").attr('data-mdlid'),
                tag = $(".dropdown-menu.dropdown-menu-right.dp-tag").find(".dropdown-item.active").attr('data-tgid');
                if (typeof(year) === 'undefined') {
                    year = 0;
                }
                if (typeof(model) === 'undefined') {
                    model = 0;
                }
                if (typeof(tag) === 'undefined') {
                    tag = 0;
                }
                _album.page++;
                $.ajax({
                    url: $("#albums").attr('data-url'),
                    type: 'POST',
                    data: {
                        _token: $("body").attr('data-csrf-token'),
                        page: _album.page,
                        year: year,
                        model: model,
                        tag: tag
                    },
                    dataType: "json",
                    success: function(data){
                        if (data.current_count > 0) {
                            for (var ii in data.rows) {
                                result += "<div class='col-6 col-md-3 col-xl-3'><div class='card'><a href='/album/detail/{0}.html' target='_blank'><img class='img-fluid' src='{1}' alt='{2}'></a><div class='card-body album-title'><p class='card-text text-truncate text-center'>{2}</p></div></div></div>".format(data.rows[ii]['id'], data.rows[ii]['cover'], data.rows[ii]['title']);
                            }
                        } else {
                            me.lock();
                            me.noData(true);
                        }
                        setTimeout(function(){
                            $('#albums').append(result);
                            me.resetload();
                            var flag = $(".navbar-toggler").attr('aria-expanded');
                            if (flag == 'true') {
                                $(".navbar-toggler").click();
                            }
                        }, 1000);
                    },
                    error: function(xhr, type){
                        me.lock();
                        me.noData();
                        me.resetload();
                    }
                });
            }
        });
    };

    _album.initDropLoad();

    $(".navbar-toggler").on('click', function(){
        var flag = $(".navbar-toggler").attr('aria-expanded');
        if (flag === 'false') {
            _album.dropload.lock();
        }
    });

    $(".dropdown-menu a").on('click', function(){
        _album.dropload.resetload();
        _album.dropload.unlock();
        $(this).parent().find(".dropdown-item").removeClass('active');
        $(this).addClass('active');
        _album.page = 0;
        _album.dropdelid = _album.dropid;
        _album.dropid = 'drop_' + Date.parse(new Date());
        var content = '<div class="container" id="' + _album.dropid + '"></div>';
        $("#" + _album.dropdelid).after(content);
        $("#" + _album.dropdelid).remove();
        $("#albums").html('');
        setTimeout(function(){
            _album.initDropLoad();
        }, 10);
    });

    $(".tz").on('click', function(){
        window.location.href = $(this).attr('data-href');
    });
});
