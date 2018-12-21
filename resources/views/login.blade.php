<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>iLegs Shadow Manage Login</title>
        <link rel="stylesheet" href="{{ $s }}/css/bootstrap.css">
        <link rel="stylesheet" href="{{ $s }}/css/style.css">
        <link rel="stylesheet" href="{{ $s }}/css/login.css">
        <link href="{{ $s }}/sweetalert2-7.32.4/sweetalert2.min.css" rel="stylesheet">
    </head>
    <body class="blank pace-done bd-login" style="background-image: url({{ $s }}/img/bg.jpg);background-size: cover;background-repeat: no-repeat;" data-csrf-token="{{ csrf_token() }}">
        <div class="wrapper">
            <section class="content">
                <div class="container-center animated slideInDown">
                    <div class="view-header">
                        <div class="header-title">
                            <h4 style="color: #f6a821;">iLegs 后台管理系统v1.0</h4>
                        </div>
                    </div>
                    <div class="panel panel-filled">
                        <div class="panel-body">
                            <form action="" id="loginForm" novalidate="">
                                <div class="form-group">
                                    <label class="control-label">帐号</label>
                                    <input type="text" name="account" class="form-control" maxlength="12">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">密码</label>
                                    <input type="password" name="password" class="form-control" maxlength="40" onpaste="return false" oncontextmenu="return false" oncopy="return false" oncut="return false">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">验证码</label>
                                    <br/>
                                    <input type="text" name="captcha" class="form-control" style="width: 60%;display: inline-block;" maxlength="4">
                                    <a href="javascript:void(0);">
                                        <img class="captcha-img" src="{{ $s }}/img/wz.png" data-src="{{ $s }}/img/wz.png" style="width: 120px;border-radius: 5px;height: 34px;">
                                    </a>
                                </div>
                                <div>
                                    <button class="btn btn-accent wl-login" type="button" style="width: 100%;">登录</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <script src="{{ $s }}/js/jquery-1.10.2.js"></script>
        <script src="{{ $s }}/js/pace.min.js"></script>
        <script src="{{ $s }}/sweetalert2-7.32.4/sweetalert2.all.min.js"></script>
        <script src="{{ $s }}/js/a_common.js"></script>
        <script src="{{ $s }}/js/login.js"></script>
    </body>
</html>
