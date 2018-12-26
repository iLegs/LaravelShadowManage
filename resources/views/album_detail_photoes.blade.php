<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{ $title }} · iLegs · 时光印象网</title>
        <meta name="keywords" content="黑丝，美腿，制服，写真，Ligui，Beautyleg，iLegs，时光印象，时光印象网">
        <meta name="description" content="时光印象网致力于图片分享，建设最高清、最专业的美图分享网站。">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
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
    <script type="text/javascript">
        var _hmt = _hmt || [];
        (function() {
            var hm = document.createElement("script");
            hm.src = "https://hm.baidu.com/hm.js?3d0f46b4bb96b324fee66680619b38ec";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
        (function(){
            var bp = document.createElement('script');
            var curProtocol = window.location.protocol.split(':')[0];
            if (curProtocol === 'https') {
                bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';
            } else {
                bp.src = 'http://push.zhanzhang.baidu.com/push.js';
            }
            var ss = document.getElementsByTagName("script")[0];
            ss.parentNode.insertBefore(bp, ss);
        })();
    </script>
</body>
</html>
