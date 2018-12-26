<!doctype html>
<html lang="en">
<head>
    <title>iLegs · 时光印象网</title>
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
    <link rel="stylesheet" href="{{ $s }}/dropload/dropload.css">
    <link rel="stylesheet" href="{{ $s }}/dripicons/webfont.css">
    <link rel="stylesheet" href="{{ $s }}/css/web/albums.css">
</head>
<body data-csrf-token="{{ csrf_token() }}">
    <nav class="navbar navbar-expand-lg navbar-light">
        <img src="{{ $s }}/img/logo.jpg" class="logo">
        <a class="navbar-brand" href="/">iLegs</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <div class="nav-item dropdown">
                        <a class="nav-item nav-link dropdown-toggle" href="#" id="dp-year" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        年份筛选
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dp-year" aria-labelledby="dp-year">
                            <a class="dropdown-item active" href="javascript:void(0);" data-year="0">全部</a>
                            @foreach($years as $year)
                            <a class="dropdown-item" href="javascript:void(0);" data-year="{{ $year }}">{{ $year }}</a>
                            @endforeach
                        </div>
                    </div>
                </li>
                <li class="nav-item">
                    <div class="nav-item dropdown">
                        <a class="nav-item nav-link dropdown-toggle" href="#" id="dp-model" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        模特筛选
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dp-model" aria-labelledby="dp-model">
                            <a class="dropdown-item active" href="javascript:void(0);" data-mdlid="0">全部</a>
                            @foreach($leg_models as $m)
                                <a class="dropdown-item" href="javascript:void(0);" data-mdlid="{{ $m['id'] }}">{{ $m['name'] }}</a>
                            @endforeach
                        </div>
                    </div>
                </li>
                <li class="nav-item">
                    <div class="nav-item dropdown">
                        <a class="nav-item nav-link dropdown-toggle" href="#" id="dp-tag" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        标签筛选
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dp-tag" aria-labelledby="dp-tag">
                            <a class="dropdown-item active" href="javascript:void(0);" data-tgid="0">全部</a>
                            @foreach($leg_tags as $tag)
                                <a class="dropdown-item" href="javascript:void(0);" data-tgid="{{ $tag['id'] }}">{{ $tag['title'] }}</a>
                            @endforeach
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <div class="row" id="albums" data-page="{{ $page }}">
        @foreach($albums as $album)
            <div class="col-6 col-md-3 col-xl-2">
                <div class="card">
                    <a href="/album/detail/{{ $album['id'] }}.html">
                        <img class="img-fluid" src="{{ $album['cover'] }}" alt="{{ $album['title'] }}">
                    </a>
                    <div class="card-body album-title">
                        <p class="card-text text-truncate text-center">{{ $album['title'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="container" id="footer"></div>
    <script src="{{ $s }}/bootstrap-4.1.3/jquery-3.3.1.min.js"></script>
    <script src="{{ $s }}/bootstrap-4.1.3/bootstrap.min.js"></script>
    <script src="{{ $s }}/bootstrap-4.1.3/popper.min.js"></script>
    <script src="{{ $s }}/dropload/dropload.min.js?v=v1.0"></script>
    <script src="{{ $s }}/js/web_albums.js?v1.0.3"></script>
    <script type="text/javascript">
        var _hmt = _hmt || [];
        (function() {
            var hm = document.createElement("script");
            hm.src = "https://hm.baidu.com/hm.js?3d0f46b4bb96b324fee66680619b38ec";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>
</body>
</html>
