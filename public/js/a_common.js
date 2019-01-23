/**
 * 公用 js 方法。
 *
 * @author    fairyin <fairyin@126.cn>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

$(document).ready(function(){
    if ("object" !== typeof _common) _common = {};

    _common.ajaxtime = 3000;

    _common.ajax = function(url, method, param, onSuccess, onFailure, token) {
        var _token = $("body").attr('data-csrf-token');
        if (_token === undefined || _token == '' || _token == null) {
            _common.failAlert('页面关键参数丢失，无法提交！');

            return false;
        }
        param['_token'] = _token;
        $.ajax({
            url: url,
            type: method,
            data: param,
            dataType: "json",
        }).done(function(data){
            onSuccess(data);
        })
        .fail(function(error){
            if (error.readyState === 0 && error.status === 0) {

                return false;
            }
            onFailure(JSON.parse(error.responseText));
        });
    };

    _common.requestUrl = {};

    $.ajaxPrefilter(function(options, originalOptions, jqXHR) {
        var key = options.url;
        if (typeof(_common.requestUrl[key]) === 'undefined') {
            _common.requestUrl[key] = jqXHR;
        } else {
            //放弃当前触发的请求
            jqXHR.abort();
            //放弃之前触发的请求
            //_common.requestUrl[key].abort();
        }
        options.complete = function(jqXHR, textStatus) {
            //限制ajax请求延迟时间
            setTimeout(function(){
                delete _common.requestUrl[key];
            }, _common.ajaxtime);
        };
    });

    _common.successAlert = function(msg, onSuccess){
        swal({type: 'success', title: msg}, onSuccess);
    };

    _common.failAlert = function(msg, onSuccess){
        swal({type: 'error', title: msg}, onSuccess);
    };

    _common.flushCaptcha = function(){
        var ll = $(".captcha-img").length;
        if (ll != 1) {
            return false;
        }
        var img = "/gencaptcha?time=" + Date.parse(new Date());
        $(".captcha-img").attr('src', img);
        $(".captcha-img").error(function() {
            _common.failAlert('验证码刷新频繁！');
            $(".captcha-img").attr('src', $(".captcha-img").attr('data-src'));
        });
        $("input[name='captcha']").val('');
    }

    _common.loadHead = function(){
        //判断 localStorage 是否有值
        var ac = window.localStorage.getItem("active");
        if (ac == undefined || ac == '' || ac == 0) {
            var ll = $(".topchange.active").length;
            if (ll <= 0) {
                $(".topchange").first().addClass('active');
            } else {
                window.localStorage.setItem("active", $(".topchange.active").attr('data-top'));
            }
        }
    };

    //_common.loadHead();

    _common.loadNode = function(){
        var url = $("body").attr('data-url');
        $(".murl").each(function(){
            var href = $(this).attr('href');
            if (href == url) {
                $(this).parent().addClass('active');
                $(this).parent().parent().parent().addClass('selected');

                return false;
            }
        });
    };

    _common.loadNode();

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
});
