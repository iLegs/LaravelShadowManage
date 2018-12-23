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
        <link rel="stylesheet" href="{{ $s }}/css/web/albums.css">
        <link rel="stylesheet" href="{{ $s }}/swiper/swiper.min.css">
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
    <script src="{{ $s }}/js/web_albums.js"></script>
</html>
