/**
 * 后台登陆js。
 *
 * @author    fairyin <fairyin@126.cn>
 * @copyright © 2018 www.liaowo8.com
 * @version   v1.0
 */

$(document).ready(function(){
    if (!$(".bd-login").length) return;

    if ("object" != typeof _login) _login = {};

    _login.submit = function(){
        var url = '/shadow/login',
            account = $("input[name='account']").val(),
            password = $("input[name='password']").val(),
            code = $("input[name='captcha']").val();
        if (account == '' || password == '') {
            _common.failAlert('请输入账号和密码～');
            return false;
        } else if (typeof(code) === 'undefined' || code.length != 4 || code === '' || code === null) {
            _common.failAlert('请输入验证码');
            return false;
        }

        let timerInterval;
        Swal({
            title: '正在登录～',
            html: '窗口将在 <strong></strong> 秒内关闭.',
            timer: 5000,
            allowOutsideClick: false,
            onBeforeOpen: () => {
                Swal.showLoading();
                timerInterval = setInterval(() => {
                    Swal.getContent().querySelector('strong')
                        .textContent = Math.ceil(Swal.getTimerLeft() / 1000)
                }, 100)
            },
            onClose: () => {
                clearInterval(timerInterval);
            }
        }).then((result) => {
            // Read more about handling dismissals
            if (result.dismiss === Swal.DismissReason.timer) {
                console.log('I was closed by the timer');
            }
        });
        var params = {
            account: account,
            password: password,
            captcha: code
        };
        _common.ajax(url, 'POST', params, function(data){
            clearInterval(timerInterval);
            window.location.href = '/shadow/login';
        }, function(error){
            _common.failAlert(error.msg);
            _common.flushCaptcha();
            clearInterval(timerInterval);
        });
    };

    $(".bd-login .wl-login").on("click", _login.submit);

    $(".bd-login .captcha-img,.bd-login .flush-captcha").on('click', _common.flushCaptcha);

    _common.flushCaptcha();

    $(document).keypress(function(event){
        if (event.keyCode == 13) {
            if ($(".sweet-alert.visible.showSweetAlert").is(':visible') == false) {
                _login.submit();
            }
        }
    });
});
