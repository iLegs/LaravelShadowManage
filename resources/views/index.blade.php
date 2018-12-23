<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>iLegs H5 Web App</title>
        <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <link rel="stylesheet" href="{{ $s }}/mui/css/mui.min.css">
        <link rel="stylesheet" href="{{ $s }}/dropload/dropload.css">
        <style>
            .mui-table-view-cell img {
                margin-bottom: 0;
            }
            .mui-table-view.mui-grid-view .mui-table-view-cell .mui-media-object {
                border-top-left-radius: 8px;
                border-top-right-radius: 8px;
            }
            .mui-table-view.mui-grid-view .mui-table-view-cell .mui-media-body {
                font-size: .6rem;
                width: 100%;
                text-overflow: ellipsis;
                color: #333;
                background-color: #FFFFFF;
                line-height: 2rem;
                height: 2rem;
                margin-top: -5px;
                padding: 0 5px;
                border-bottom-left-radius: 8px;
                border-bottom-right-radius: 8px;
            }
            .mui-table-view {
                background-color: #efeff4;
            }
            .mui-pull-bottom-pocket {
                height: 50px;
            }
            .mui-pull-caption {
                color: #00000045;
            }
        </style>
    </head>
    <body data-csrf-token="{{ csrf_token() }}">
        <header class="mui-bar mui-bar-nav">
            <h1 class="mui-title">首页</h1>
        </header>
        <div class="mui-content">
            <ul class="mui-table-view mui-grid-view"></ul>
        </div>
    </body>
    <script src="{{ $s }}/dropload/zepto.min.js"></script>
    <script src="{{ $s }}/dropload/dropload.min.js?v=v1.0"></script>
    <script src="{{ $s }}/mui/js/mui.min.js"></script>
    <script type="text/javascript">
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
    </script>
</html>
