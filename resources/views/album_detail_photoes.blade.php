<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{ $title }} · iLegs</title>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport"/>
        <meta content="black" name="apple-mobile-web-app-status-bar-style"/>
        <meta content="telephone=no,email=no,adress=no" name="format-detection"/>
        <link rel="stylesheet" href="{{ $s }}/mui/css/mui.min.css">
        <link href="{{ $s }}/lightGallery-1.6.11/lightgallery.min.css" rel="stylesheet">
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
                line-height: 1.2rem;
                height: 1.2rem;
                margin-top: -5px;
                padding: 0 5px;
                border-bottom-left-radius: 8px;
                border-bottom-right-radius: 8px;
            }
            .mui-table-view {
                background-color: #efeff4;
            }
            .mui-table-view-cell img {
                border-top-left-radius: 5px;
                border-top-right-radius: 5px;
            }
        </style>
</head>
<body class="home">
    <header class="mui-bar mui-bar-nav">
        <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
        <h1 class="mui-title">{{ $title }}</h1>
    </header>
    <div class="mui-content">
        <ul class="mui-table-view mui-grid-view" id="lightgallery">
            @php
                $ii = 1;
            @endphp
            @foreach($photoes as $photo)
            <li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-2" data-responsive="{{ $photo['preview'] }}"  data-src="{{ $photo['original'] }}" data-sub-html="">
                <a href="javascript:void(0);">
                    <img class="mui-media-object img-responsive lazyload" src="{{ $s }}/img/wz.png" data-src="{{ $photo['preview'] }}">
                    <div class="mui-media-body">{{ $ii }}</div>
                </a>
            </li>
                @php
                    $ii += 1;
                @endphp
            @endforeach
        </ul>
    </div>
    <script src="{{ $s }}/js/jquery-1.10.2.js"></script>
    <script src="{{ $s }}/lightGallery-1.6.11/picturefill.min.js"></script>
    <script src="{{ $s }}/lightGallery-1.6.11/lightgallery-all.min.js"></script>
    <script src="{{ $s }}/lightGallery-1.6.11/jquery.mousewheel.min.js"></script>
    <script src="{{ $s }}/mui/js/mui.min.js"></script>
    <script src="{{ $s }}/js/lazyload.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#lightgallery').lightGallery({
                share: false,
                download: false,
                controls: false,
                exThumbImage: false,
                thumbnail: true,
                showThumbByDefault: false,
                animateThumb: true,
                currentPagerPosition: 'middle',
                thumbWidth: 100,
                thumbMargin: 5
            });

            $("img.lazyload").lazyload();
        });

        window.onload = function() {
            if (document.documentElement.scrollHeight <= document.documentElement.clientHeight) {
                bodyTag = document.getElementsByTagName('body')[0];
                bodyTag.style.height = document.documentElement.clientWidth / screen.width * screen.height + 'px';
            }
            setTimeout(function() {
                window.scrollTo(0, 1);
            }, 0);
        };
</script>
</body>
</html>
