<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>iLegs H5 Web App</title>
        <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <link rel="stylesheet" href="{{ $s }}/mui/css/mui.min.css">
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
        </style>
    </head>
    <body>
        <header class="mui-bar mui-bar-nav">
            <h1 class="mui-title">首页</h1>
        </header>
        <div class="mui-content">
            <ul class="mui-table-view mui-grid-view">
                @foreach($albums as $album)
                <li class="mui-table-view-cell mui-media mui-col-xs-6">
                    <a href="/album/detail/{{ $album['id'] }}.html">
                        <img class="mui-media-object" src="{{ $album['cover'] }}">
                        <div class="mui-media-body">{{ $album['title'] }}</div>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </body>
    <script src="{{ $s }}/mui/js/mui.min.js"></script>
    <script>
        mui.init({
            swipeBack:true //启用右滑关闭功能
        });
    </script>
</html>
