<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>iLegs H5 Web App</title>
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
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #FFF;">
        <img src="{{ $s }}/img/logo.jpg" class="logo">
        <a class="navbar-brand" href="#">iLegs</a>
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
    <div class="row" id="albums"></div>
    <div class="container" id="footer"></div>
    <script src="{{ $s }}/bootstrap-4.1.3/jquery-3.3.1.min.js"></script>
    <script src="{{ $s }}/bootstrap-4.1.3/bootstrap.min.js"></script>
    <script src="{{ $s }}/bootstrap-4.1.3/popper.min.js"></script>
    <script src="{{ $s }}/dropload/dropload.min.js?v=v1.0"></script>
    <script src="{{ $s }}/js/web_albums.js?v1.2"></script>
</body>
</html>
