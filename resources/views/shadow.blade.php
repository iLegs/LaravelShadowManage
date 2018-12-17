<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>iLegs Shadow Manage Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="icon" type="image/png" href="{{ $s }}/assets/i/favicon.png">
    <link rel="apple-touch-icon-precomposed" href="{{ $s }}/assets/i/app-icon72x72@2x.png">
    <script src="{{ $s }}/assets/js/echarts.min.js"></script>
    <link rel="stylesheet" href="{{ $s }}/assets/css/amazeui.min.css" />
    <link rel="stylesheet" href="{{ $s }}/assets/css/app.css">
    <style type="text/css">
        table th, table td {
            text-align: center;
        }
    </style>
    @section('style')@show
</head>

<body data-csrf-token="{{ csrf_token() }}">
    <div class="am-g tpl-g">
        <header>
            <div class="am-fl tpl-header-logo">
                <a href="javascript:;"><img src="{{ $s }}/img/logo.jpg" style="width: 45px;border-radius: 20px;"></a>
            </div>
            <div class="tpl-header-fluid">
                <div class="am-fl tpl-header-switch-button am-icon-list"></div>
                <div class="am-fr tpl-header-navbar">
                    <ul>
                        <li class="am-text-sm tpl-header-navbar-welcome">
                            <a href="javascript:;">欢迎你, <span>{{ $user->account  }}</span> </a>
                        </li>
                        <li class="am-text-sm">
                            <a href="/shadow/logout">
                                <span class="am-icon-sign-out"></span> 退出
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <div class="left-sidebar">
            <ul class="sidebar-nav">
                <li class="sidebar-nav-link">
                    <a href="/shadow/main" @if(isset($active) && $active == 'home')class="active"@endif>
                        <i class="am-icon-home sidebar-nav-link-logo"></i> 首页
                    </a>
                </li>
                <li class="sidebar-nav-link">
                    <a href="javascript:;" class="sidebar-nav-sub-title">
                        <i class="am-icon-tags sidebar-nav-link-logo"></i> 标签管理
                        <span class="am-icon-chevron-down am-fr am-margin-right-sm sidebar-nav-sub-ico"></span>
                    </a>
                    <ul class="sidebar-nav sidebar-nav-sub">
                        <li class="sidebar-nav-link">
                            <a href="/shadow/tags/manage" @if(isset($active) && $active == 'tags_m')class="active"@endif>
                                <span class="am-icon-angle-right sidebar-nav-link-logo"></span>标签列表
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="tpl-content-wrapper">
            @section('content')@show
        </div>
    </div>
    </div>
    <script src="{{ $s }}/assets/js/jquery.min.js"></script>
    <script src="{{ $s }}/assets/js/theme.js"></script>
    <script src="{{ $s }}/assets/js/app.js"></script>
    @section('script')@show
</body>

</html>
