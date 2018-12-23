String.prototype.format = function () {
    var args = arguments;
    var reg = /\{(\d+)\}/g;
    return this.replace(reg, function (g0, g1) {
        return args[+g1];
    });
};

$(function(){
    var page = 0;
    var size = 8;
    $('.mui-content').dropload({
        scrollArea: window,
        loadDownFn: function(me){
            var result = '';
            page++;
            $.ajax({
                url: '/',
                type: 'POST',
                data: {
                    _token: $("body").attr('data-csrf-token'),
                    page: page
                },
                dataType: "json",
                success: function(data){
                    if (data.current_count > 0) {
                        for (var ii in data.rows) {
                            result += "<li class='mui-table-view-cell mui-media mui-col-xs-6 mui-col-sm-2'><a href='/album/detail/{0}.html'><img class='mui-media-object' src='{1}'><div class='mui-media-body'>{2}</div></a></li>".format(data.rows[ii]['id'], data.rows[ii]['cover'], data.rows[ii]['title']);
                        }
                    } else {
                        // 锁定
                        me.lock();
                        // 无数据
                        me.noData();
                    }
                    $('.mui-table-view.mui-grid-view').append(result);
                    me.resetload();
                },
                error: function(xhr, type){
                    me.resetload();
                }
            });
        }
    });
});
