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
        .nowrap dd {
            width: auto !important;
            margin-left: -4px;
        }
        .pageFormContent.tag label {
            width: 70px;
        }
        .pageFormContent.mdl label {
            width: 116px;
        }
        .grid .gridTbody td div {
            height: auto;
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
    <!--
    <script src="{{ $s }}/dwz/js/dwz.core.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/js/dwz.util.date.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/js/dwz.validate.method.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/js/dwz.barDrag.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/js/dwz.drag.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/js/dwz.tree.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/js/dwz.accordion.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/js/dwz.ui.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/js/dwz.theme.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/js/dwz.switchEnv.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/js/dwz.alertMsg.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/js/dwz.contextmenu.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/js/dwz.navTab.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/js/dwz.tab.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/js/dwz.resize.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/js/dwz.dialog.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/js/dwz.dialogDrag.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/js/dwz.sortDrag.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/js/dwz.cssTable.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/js/dwz.stable.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/js/dwz.taskBar.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/js/dwz.ajax.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/js/dwz.pagination.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/js/dwz.database.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/js/dwz.datepicker.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/js/dwz.effects.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/js/dwz.panel.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/js/dwz.checkbox.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/js/dwz.history.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/js/dwz.combox.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/js/dwz.file.js" type="text/javascript"></script>
    <script src="{{ $s }}/dwz/js/dwz.print.js" type="text/javascript"></script>
    -->
    <script src="{{ $s }}/dwz/js/dwz.regional.zh.js" type="text/javascript"></script>
    <script type="text/javascript">
    $(function(){
        DWZ.init("{{ $s }}/dwz/dwz.frag.xml", {
            loginUrl:"/shadow/login",  // 跳到登录页面
            statusCode:{
                ok: 200, error: 403, timeout: 301
            },
            pageInfo:{
                pageNum: "pageNum",
                numPerPage: "numPerPage",
                orderField: "orderField",
                orderDirection: "orderDirection",
                pageNumShown: 20
            },
            keys: {
                statusCode: "statusCode",
                message: "message"
            },
            ui:{
                hideMode:'offsets'
            }, //【可选】hideMode:navTab组件切换的隐藏方式，支持的值有’display’，’offsets’负数偏移位置的值，默认值为’display’
            debug: false,    // 调试模式 【true|false】
            callback:function(){
                initEnv();
                $("#themeList").theme({themeBase:"themes"}); // themeBase 相对于index页面的主题base路径
            }
        });
        $.ajaxSetup({
           headers: { 'X-CSRF-Token': $('body').attr('data-csrf-token') }
        });
        var changeImg = function(){
            var ll = $(".gridTbody img").length;
            if (ll <= 0) {
                setTimeout(function(){
                    changeImg();
                }, 100);
            }
            $(".gridTbody img").each(function(){
                var w = $(this).width();
                var h = $(this).height();
                var tr = $(this).parent().parent().parent();
                if (w > h) {
                    $(tr).find("input[name='ids[]']").attr('checked', 'checked');
                }
            });
        }

        setTimeout(function(){
            changeImg();
        }, 1000);
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
                <li>
                    <a href="/shadow/logout">退出</a>
                </li>
            </ul>
        </div>
    </div>
    <div id="leftside">
        <div id="sidebar_s">
            <div class="collapse">
                <div class="toggleCollapse">
                    <div></div>
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
                        <li>
                            <a href="/shadow/tags/manage" target="navTab" rel="main">
                                标签列表
                            </a>
                        </li>
                        <li>
                            <a href="/shadow/legmodels/manage" target="navTab" rel="main">
                                模特列表
                            </a>
                        </li>
                        <li>
                            <a href="/shadow/leglibs/manage" target="navTab" rel="main">
                                图库列表
                            </a>
                        </li>
                        <li>
                            <a href="/shadow/albums/manage" target="navTab" rel="albums">
                                专辑列表
                            </a>
                        </li>
                        <li>
                            <a href="/shadow/photoes/manage" target="navTab" rel="pics">
                                图片列表
                            </a>
                        </li>
                        <li>
                            <a href="/shadow/qiniufile/manage" target="navTab" rel="qiniu">
                                七牛云文件搜索
                            </a>
                        </li>
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
                            <a href="javascript:;">
                                <span>
                                    <span class="home_icon">我的主页</span>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <ul class="tabsMoreList">
                <li>
                    <a href="javascript:;">我的主页</a>
                </li>
            </ul>
            <div class="navTab-panel tabsPageContent layoutBox">
                <div class="page unitBox"></div>
            </div>
        </div>
    </div>
</div>
<div id="footer">
    Copyright &copy; 2018
    <a href="//github.com/fairyin" target="dialog">Fairyin</a>
</div>
</body>
</html>
