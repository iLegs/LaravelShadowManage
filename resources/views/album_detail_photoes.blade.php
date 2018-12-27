<!doctype html>
<html lang="en">
<head>
    <title>{{ $album->title }} · iLegs · 时光印象网</title>
    <meta name="keywords" content="黑丝，美腿，制服，写真，Ligui，Beautyleg，iLegs，时光印象，时光印象网">
    <meta name="description" content="时光印象网致力于图片分享，建设最高清、最专业的美图分享网站。">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="full-screen" content="yes">
    <meta name="x5-fullscreen" content="true">
    <link rel="stylesheet" href="{{ $s }}/bootstrap-4.1.3/bootstrap.min.css">
    <link rel="stylesheet" href="{{ $s }}/dripicons/webfont.css">
    <link rel="stylesheet" href="{{ $s }}/css/web/album_detail.css?v1.0.1">
    <link href="{{ $s }}/lightGallery-1.6.11/lightgallery.min.css" rel="stylesheet">
    <link href="{{ $s }}/sweetalert2-7.32.4/sweetalert2.min.css" rel="stylesheet">
</head>
<body data-csrf-token="{{ csrf_token() }}">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand" href="/"><img src="{{ $s }}/img/logo.jpg" class="logo" alt="iLegs · 时光印象网">&nbsp;iLegs</a>
        </nav>
        <div class="row">
            <div class="col-12 col-md-8">
                <button type="button" class="btn icon" data-icon="&#xe009;"></button>
                 <button type="button" class="btn btn-outline-secondary">{{ $album->title }}</button>
            </div>
            <div class="col-12 col-md-4 right">
                <button type="button" class="btn icon" data-icon="]"></button>
                <button type="button" class="btn btn-outline-secondary">发行时间：{{ $album->date }}</button>
            </div>
            <div class="col-12">
                <button type="button" class="btn icon" data-icon="&#xe04c;">
                </button>
                @foreach($album->getTags() as $tag)
                    <button type="button" class="btn btn-outline-success tz" data-href="/0_0_{{ $tag['id'] }}.html">{{ $tag['title'] }}</button>
                @endforeach
            </div>
        </div>
        <div class="row" id="lightgallery">
            @php $ii = 1; @endphp
            @foreach($photoes as $photo)
                <div class="col-6 col-md-3" data-responsive="{{ $photo['preview'] }}" data-src="{{ $photo['original'] }}" data-sub-html="{{ $photo['id']}}">
                    <div class="card">
                        <img class="img-fluid lazyload" src="{{ $s }}/img/image.png" data-src="{{ $photo['preview'] }}" alt="{{ $album->title }}">
                        <div class="card-body">
                            <p class="card-text text-center">{{ $ii }}</p>
                        </div>
                    </div>
                </div>
                @php $ii += 1; @endphp
            @endforeach
        </div>
    </div>
    <script src="{{ $s }}/bootstrap-4.1.3/jquery-3.3.1.min.js"></script>
    <script src="{{ $s }}/js/lazyload.min.js"></script>
    <script src="{{ $s }}/js/web_album_detail.js?v1.0.1"></script>
    <script src="{{ $s }}/lightGallery-1.6.11/picturefill.min.js"></script>
    <script src="{{ $s }}/lightGallery-1.6.11/lightgallery-all.min.js"></script>
    <script src="{{ $s }}/lightGallery-1.6.11/jquery.mousewheel.min.js"></script>
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
