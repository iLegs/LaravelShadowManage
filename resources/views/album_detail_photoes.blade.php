<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{ $title }} · iLegs</title>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport"/>
        <meta content="black" name="apple-mobile-web-app-status-bar-style"/>
        <meta content="telephone=no,email=no,adress=no" name="format-detection"/>
        <link rel="stylesheet" href="{{ $s }}/mui/css/mui.min.css">
        <link href="{{ $s }}/lightGallery-1.6.11/lightgallery.min.css" rel="stylesheet">
        <link href="{{ $s }}/sweetalert2-7.32.4/sweetalert2.min.css" rel="stylesheet">
        <link href="{{ $s }}/css/web/album_detail.css" rel="stylesheet">
</head>
<body class="home" data-csrf-token="{{ csrf_token() }}">
    <header class="mui-bar mui-bar-nav">
        <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
        <h1 class="mui-title">{{ $title }}</h1>
    </header>
    <div class="mui-content">
        @php $ii = 1; @endphp
        <ul class="mui-table-view mui-grid-view" id="lightgallery">
            @foreach($photoes as $photo)
                <li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-2" data-responsive="{{ $photo['preview'] }}" data-src="{{ $photo['original'] }}" data-sub-html="{{ $photo['id']}}">
                    <a href="javascript:void(0);">
                        <img class="mui-media-object img-responsive lazyload" src="{{ $s }}/img/wz.png" data-src="{{ $photo['preview'] }}">
                        <div class="mui-media-body">{{ $ii }}</div>
                    </a>
                </li>
                @php $ii += 1; @endphp
            @endforeach
        </ul>
    </div>
    <script src="{{ $s }}/js/jquery-1.10.2.js"></script>
    <script src="{{ $s }}/js/a_common.js"></script>
    <script src="{{ $s }}/js/web_album_detail.js"></script>
    <script src="{{ $s }}/lightGallery-1.6.11/picturefill.min.js"></script>
    <script src="{{ $s }}/lightGallery-1.6.11/lightgallery-all.min.js"></script>
    <script src="{{ $s }}/lightGallery-1.6.11/jquery.mousewheel.min.js"></script>
    <script src="{{ $s }}/js/lazyload.min.js"></script>
    <script src="{{ $s }}/sweetalert2-7.32.4/sweetalert2.all.min.js"></script>
</body>
</html>
