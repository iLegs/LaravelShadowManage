<!doctype html>
<html lang="en">
<head>
    <title>iLegs · 时光印象网 @yield('title')</title>
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
    <link rel="stylesheet" href="{{ $s }}/css/web/style.css">
    @yield('style')
</head>
<body data-csrf-token="{{ csrf_token() }}">
    <header class="container-fluid iheader">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand" href="/">iLegs</a>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-item nav-link @if(!isset ($active) || $active == 'index') active @endif" href="/">Home Page</a>
                    <!--
                    <a class="nav-item nav-link" href="/models.html">Models List </a>
                    <a class="nav-item nav-link" href="/tags.html">Tags List </a>
                    -->
                    @foreach($libs as $lib)
                        @if($lib['count'] > 5)
                            <a class="nav-item nav-link @if(isset ($active) && $active == $lib['url']) active @endif" target="_blank" href="/lib/{{ $lib['url'] }}/list.html">{{ $lib['short_title'] }}
                                <span class="badge badge-warning">{{ $lib['count'] }}</span>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
            <!--
            <a class="navbar-brand float-right" href="/login.html">Sign in</a>
            <a class="navbar-brand float-right" href="/register.html">Sign up</a>
            -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </nav>
    </header>
    @yield('body')
    <footer class="footer">
        <div class="container p-2 p-md-3">
            <ul class="list-inline">
                <li class="list-inline-item">
                    Friends：
                </li>
                <li class="list-inline-item" target="_blank">
                    <a href="https://github.com/fairyin">Fairyin丶</a>
                </li>
            </ul>
            <ul class="list-inline">
                <li class="list-inline-item">
                    Designed by <a href="https://github.com/ilegs" target="_blank" rel="noopener">@iLegs Team</a> .
                </li>
            </ul>
            <p>
                <a href="http://www.miitbeian.gov.cn/publish/query/indexFirst.action" target="_blank" rel="license noopener">鄂ICP备16006107号-2</a>
            </p>
            <p>
                Disclaimer: Some of the resources collected by this website come from the Internet. If you find any works infringing your intellectual property rights on this website, please contact us and we will modify or delete them in time.
            </p>
            <p>
                <a href="mailto:fairyin@vip.qq.com">contact us</a>
            </p>
        </div>
    </footer>
    <script src="{{ $s }}/bootstrap-4.1.3/jquery-3.3.1.min.js"></script>
    <script src="{{ $s }}/bootstrap-4.1.3/bootstrap.min.js"></script>
    <script src="{{ $s }}/bootstrap-4.1.3/popper.min.js"></script>
    @yield('script')
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
