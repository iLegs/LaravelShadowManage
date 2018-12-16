<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iLegs Shadow Manage Login</title>
    <link rel="stylesheet" href="{{ $s }}/css/bootstrap.css">
    <link rel="stylesheet" href="{{ $s }}/css/style(1).css">
</head>
<body class="blank pace-done" style="background-image: url({{ $s }}/img/bg.jpg);background-size: cover;background-repeat: no-repeat;">
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
                                <label class="control-label" for="username">帐号</label>
                                <input type="text" placeholder="" title="Please enter you username" required="" value="" name="username" id="username" class="form-control" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="password">密码</label>
                                <input type="password" title="Please enter your password" placeholder="" required="" value="" name="password" id="password" class="form-control" autocomplete="off">
                            </div>
                            <div>
                                <button class="btn btn-accent" style="width: 100%;">登录</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script src="{{ $s }}/js/pace.min.js"></script>
</body>
</html>
