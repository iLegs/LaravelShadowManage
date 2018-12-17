<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>iLegs后台管理系统v1.0</title>
    <link href="{{ $s }}/dwz/themes/default/style.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="{{ $s }}/dwz/themes/css/core.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="{{ $s }}/dwz/themes/css/print.css" rel="stylesheet" type="text/css" media="print"/>
    <link href="{{ $s }}/dwz/uploadify/css/uploadify.css" rel="stylesheet" type="text/css" media="screen"/>
    <style type="text/css">
        .buttonActive button, .button button{
            padding: 1px 7px 2px;
        }
        span.error {
            width: auto;
        }
    </style>
    <!--[if IE]>
    <link href="{{ $s }}/dwz/themes/css/ieHack.css" rel="stylesheet" type="text/css" media="screen"/>
    <![endif]-->
    <!--[if lt IE 9]><script src="{{ $s }}/dwz/js/speedup.js" type="text/javascript"></script><script src="{{ $s }}/dwz/js/jquery-1.11.3.min.js" type="text/javascript"></script><![endif]-->
    <!--[if gte IE 9]><!--><script src="{{ $s }}/dwz/js/jquery-2.1.4.min.js" type="text/javascript"></script><!--<![endif]-->
    <script src="{{ $s }}/dwz/js/jquery.cookie.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/js/jquery.validate.js" type="text/javascript"></script>
    <!--<script src="{{ $s }}/dwz/js/jquery.bgiframe.js" type="text/javascript"></script>-->
    <script src="{{ $s }}/dwz/xheditor/xheditor-1.2.2.min.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/xheditor/xheditor_lang/zh-cn.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/uploadify/scripts/jquery.uploadify.js" type="text/javascript"></script>
    <script type="text/javascript" src="{{ $s }}/dwz/chart/echarts.min.js"></script>
    <script src="{{ $s }}/dwz/dwz.min.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/js/dwz.regional.zh.js" type="text/javascript"></script>
    <script type="text/javascript">
    $(function(){
        DWZ.init("{{ $s }}/dwz/dwz.frag.xml", {
            loginUrl:"/shadow/login",
            loginTitle:"登录",  // 弹出登录对话框
            //loginUrl:"/shadow/login",  // 跳到登录页面
            statusCode:{
                ok: 200, error: 403,
            },
            pageInfo:{
                pageNum: "pageNum",
                numPerPage: "numPerPage",
                orderField: "orderField",
                orderDirection: "orderDirection"
            },
            keys: {
                statusCode: "statusCode",
                message: "message"
            },
            ui:{
                hideMode:'offsets'
            }, //【可选】hideMode:navTab组件切换的隐藏方式，支持的值有’display’，’offsets’负数偏移位置的值，默认值为’display’
            debug:true,    // 调试模式 【true|false】
            callback:function(){
                initEnv();
                $("#themeList").theme({themeBase:"themes"}); // themeBase 相对于index页面的主题base路径
            }
        });
        $.ajaxSetup({
           headers: { 'X-CSRF-Token': $('body').attr('data-csrf-token') }
        });
    });
    </script>
</head>
<body data-csrf-token="{{ csrf_token() }}">
<div id="layout">
    <div id="header">
        <div class="headerNav">
            <ul class="nav">
                <li>
                    <span style="color: white;">
                        欢迎您，fairyin
                    </span>
                </li>
                <li ><a href="/shadow/logout">退出</a></li>
            </ul>
        </div>
    </div>
    <div id="leftside">
        <div id="sidebar_s">
            <div class="collapse">
                <div class="toggleCollapse">
                    <div>
                    </div>
                </div>
            </div>
        </div>
        <div id="sidebar">
            <div class="toggleCollapse">
                <h2>左侧菜单</h2>
                <div>收缩</div>
            </div>
            <div class="accordion" fillSpace="sidebar">
                <div class="accordionHeader">
                    <h2><span>Folder</span>图库管理</h2>
                </div>
                <div class="accordionContent">
                    <ul class="tree treeFolder">
                        <li><a href="/shadow/tags/manage" target="navTab" rel="main">标签列表</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div id="container">
        <div id="navTab" class="tabsPage">
            <div class="tabsPageHeader">
                <div class="tabsPageHeaderContent">
                    <ul class="navTab-tab">
                        <li tabid="main" class="main">
                            <a href="javascript:;"><span><span class="home_icon">我的主页</span></span></a>
                        </li>
                    </ul>
                </div>
            </div>
            <ul class="tabsMoreList">
                <li><a href="javascript:;">我的主页</a></li>
            </ul>
            <div class="navTab-panel tabsPageContent layoutBox">
                <div class="page unitBox">
                </div>
            </div>
        </div>
    </div>
</div>
<div id="footer">Copyright &copy; 2018 <a href="demo_page2.html" target="dialog">Fairyin</a></div>
</body>
</html>