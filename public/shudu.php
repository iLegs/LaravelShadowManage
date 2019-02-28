<?php
    $a_shudu = array(
        0 => array(0, 0, 4, 0, 0, 0, 0, 0, 0),
        1 => array(8, 0, 7, 0, 0, 5, 0, 0, 1),
        2 => array(0, 0, 0, 0, 9, 3, 0, 0, 0),
        3 => array(0, 0, 0, 7, 0, 4, 0, 0, 0),
        4 => array(0, 3, 0, 0, 0, 0, 0, 2, 0),
        5 => array(0, 2, 0, 0, 0, 0, 5, 0, 0),
        6 => array(0, 6, 0, 0, 0, 0, 9, 0, 0),
        7 => array(0, 0, 0, 0, 0, 0, 0, 6, 0),
        8 => array(0, 0, 1, 8, 0, 0, 0, 0, 0),
    );
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <style type="text/css">
        td {
            width: 50px;
            height: 50px;
            border:#ffb10087 0.5px dashed;
            font-weight: 900;
            cursor: pointer;
        }
        .d0 {
            border-top: 2px orange solid;
        }
        .r0 {
            border-left: 2px orange solid;
        }
        .d3, .d6 {
            border-top: none;
        }
        .r3, .r6{
            border-left: none;
        }
        .hasv {
            color:#3e3100;font-size:24px;font-weight: 900;
        }
        .d2, .d5, .d8 {
            border-bottom: 2px orange solid;
        }
        .r2, .r5 ,.r8{
            border-right: 2px orange solid;
        }
        .active {
            background-color: #ffa50047;
        }
        .custom {
            background-color: #CCFF00 !important;
        }
        .tryred {
            background-color: #FFFF00 !important;
        }
        .sure1{
            background-color: #CCFFCC;
        }
        .sure2 {
            background-color: #FF9999;
        }
        .sure3 {
            background-color: #FF99CC;
        }
        .sure4 {
            background-color: #FF99FF;
        }
        .sure5 {
            background-color: #00CCFF
        }
        .sure6 {
            background-color: #FFCC99;
        }
        .sure7 {
            background-color: #FFCCCC;
        }
        .sure8 {
            background-color: #FFCCFF;
        }
        .sure9 {
            background-color: #FFFF99;
        }
        .sure10 {
            background-color: #FFFFCC;
        }
    </style>
</head>
<body>
    <div style="margin-left: 30%;">
        <table style="text-align: center;" cellspacing="0">
            <?php
                $s_str = '';
                foreach ($a_shudu as $ss => $arr) {
                    $s_str .= '<tr>';
                    foreach ($arr as $key => $vv) {
                        $i_r = floor($key / 3) + 1;
                        $i_d = floor($ss / 3);
                        $i_area = $i_d * 3 + $i_r;
                        $s_str .= '<td class="' . ($vv == 0 ? 'hasnone ' : 'hasv ') . 'r' . $key . ' d' . $ss . ' area' . $i_area . '" data-area=' . $i_area . ' data-r="' . $key . '" data-d="' . $ss . '">' . ($vv == 0 ? '' : $vv) . '</td>';
                    }
                    $s_str .= '</tr>';
                }
                echo $s_str;
            ?>
        </table>
        <br>
        <input type="button" name="start" id="start" value="开始">
    </div>
    <script src="//code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script type="text/javascript">
        var sure = 0;
        var _count = 0;
        var _total = 0;
        var _maybe = [];

        var try_random = false;
        var try_key = false;

        var sleep = function (ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        };

        _numActive = function(num){
            clear();
            $(".hasv").each(function(){
                var v = $(this).html();
                if (v == num) {
                    var r = $(this).attr('data-r');
                    var d = $(this).attr('data-d');
                    _activeHs(r, d);
                }
            });
            _eachArea();
            var flag = false;
            //获取独立的区块
            for (var ii = 1; ii <= 9; ii++) {
                var ll = $(".hasnone.area" + ii).not(".active").length;
                if (ll === 1) {
                    var rr = $(".hasnone.area" + ii).not(".active").attr('data-r');
                    var dd = $(".hasnone.area" + ii).not(".active").attr('data-d');
                    bindOne(rr, dd, [num]);
                    flag = true;
                    continue;
                }
                _setCache(ii, num);
            }
            if (flag === true) {
                return true;
            }

            return false;
        };

        var _eachArea = function () {
            //添加检验规则
            for (var num = 1; num <= 9; num++) {
                var ll = $(".hasnone.area" + num).not(".active").length;
                //判断长度
                if (ll <= 3 || ll > 0) {
                    //判断区块干扰性
                    $(".hasnone.area" + num).not(".active").each(function(){
                        var r = $(this).attr('data-r');
                        var d = $(this).attr('data-d');
                        var rll = $(".hasnone.area" + num + ".r" + r).not(".active").length;
                        var dll = $(".hasnone.area" + num + ".d" + d).not(".active").length;
                        if (rll == ll) {
                            if ($(".r" + r).not(".active").not('.area' + num).length > 0) {
                                $(".r" + r).not(".active").not('.area' + num).each(function(){
                                    var rrr = $(this).attr('data-r');
                                    var ddd = $(this).attr('data-d');
                                    _activeYueShu(rrr, ddd);
                                });
                            }
                        } else if (dll == ll){
                            $(".d" + d).not(".active").not('.area' + num).each(function(){
                                var rrr = $(this).attr('data-r');
                                var ddd = $(this).attr('data-d');
                                _activeYueShu(rrr, ddd);
                            });
                        }
                    });
                }
            }
        }

        var _setCache = function(area, num){
            $(".hasnone.area" + area).not(".active").each(function(){
                var rr = $(this).attr('data-r');
                var dd = $(this).attr('data-d');
                if (typeof(_maybe['.r' + rr + '.d' + dd]) === 'undefined') {
                    _maybe['.r' + rr + '.d' + dd] = [];
                }
                if (_maybe['.r' + rr + '.d' + dd].indexOf(num) === -1) {
                    _maybe['.r' + rr + '.d' + dd].push(num);
                }
            });
        }

        var clear = function(){
            $("td").removeClass('active');
            $("td").removeClass('custom');
        };

        var _getNumMayBeArr = function (r, d){
            if ($(".r" + r +".d" + d).hasClass('hasv')) {
                return false;
            }
            var _r = [];
            $(".hasv.r" + r).each(function(){
                _r.push(parseInt($(this).html()));
            });
            _r = uniq(_r);
            _r.sort();
            //console.debug('_r' + r + ' :' + _r);
            var _d = [];
            $(".hasv.d" + d).each(function(){
                _d.push(parseInt($(this).html()));
            });
            _d = uniq(_d);
            _d.sort();
            //console.debug('_d' + d + ' :' + _d);
            var _a = [];
            var a = $(".r" + r + ".d" + d).attr('data-area');
            $(".hasv.area" + a).each(function(){
                _a.push(parseInt($(this).html()));
            });
            _a = uniq(_a);
            _a.sort();
            //console.debug('_a' + a + ' :' + _a);
            var _jihe = _a.concat(_r);
            _jihe = _jihe.concat(_d);
            _jihe = uniq(_jihe);
            _jihe.sort();

            if (typeof(_maybe[".r" + r + ".d" + d]) !== 'undefined') {
                for (var ss in _maybe["r" + r + "d" + d]) {
                    if (_jihe.indexOf(_maybe["r" + r + "d" + d][ss]) !== -1 && try_random === false) {
                        delete _maybe[".r" + r + ".d" + d][s];
                    }
                }
            } else {
                _maybe[".r" + r + ".d" + d] = [];
                for (var i = 1; i < 9; i++) {
                    if (_jihe.indexOf(i) === -1) {
                        _maybe[".r" + r + ".d" + d].push(i);
                    }
                }
            }

            clear();
            _maybe[".r" + r + ".d" + d].sort();

            return _maybe[".r" + r + ".d" + d];
        };

        //过滤规则1：匹配所有区块，发现唯一区块进行填充
        var _beforeInit = async function (){
            sure++;
            _maybe = [];
            //筛选所有区块
            for (var i = 1; i <= 9; i++) {
                var flag = _numActive(i);
                if (flag === true) {
                    i = 0;
                    _maybe = [];
                    continue;
                }
                await sleep(1);
            }
            _init();
        };

        _sureMay = [];

        async function _activeHs(r, d){
            $(".r" + r).addClass('active');
            $(".d" + d).addClass('active');
            var area = $(".r" + r + ".d" + d).attr('data-area');
            $(".area" + area).addClass('active');
            $(".r" + r + ".d" + d).addClass('custom');
        };

        async function _activeYueShu(r, d){
            $(".r" + r + ".d" + d).addClass('active');
        };

        var bindOne = function(r, d, maybe){
            if (try_random === true && typeof(maybe[0]) !== 'undefined') {
                $(".r" + r + ".d" + d).html(maybe[0]);
                $(".r" + r + ".d" + d).addClass('tryred');
            } else {
                $(".r" + r + ".d" + d).html(maybe[0]);
            }
            $(".r" + r + ".d" + d).addClass('sure' + sure);
            $(".r" + r + ".d" + d).addClass('hasv');
            $(".r" + r + ".d" + d).removeClass('hasnone');
        };

        /**
         * 初始化加载。
         * @return mixed
         */
        var _init = function(){
            sure++;
            var lls = $(".hasv").length;
            var stop_flag = false;
            for (var d = 0; d < 9; d++) {
                var flag = false;
                for (var r = 0; r < 9; r++) {
                    var maybe = _getNumMayBeArr(r, d);
                    if (false !== maybe && maybe.length === 1) {
                        bindOne(r, d, maybe);
                        flag = true;

                        break;
                    }
                }
                if (flag === true) {
                    stop_flag = true;

                    break;
                }
            }
            if (stop_flag === true) {
                _beforeInit();

                return false;
            }
            var new_lls = $(".hasv").length;
            if (lls === new_lls && try_random === false) {
                //_loadRandom();
            }
        };

        var _loadRandom = function(){
            var tmp_arr = [];
            for (var ii in _maybe) {
                if (_maybe[ii].length === 2) {
                    try_random = true;
                    console.log(ii, _maybe[ii]);
                }
            }
        };

        var _resetNum = function(){
            $(".tryred").addClass('hasnone');
            $(".tryred.hasv").removeClass('hasv');
            $(".tryred").html('');
            for (var i = sure; i > 0; i--) {
                $(".tryred").removeClass('sure' + i);
            }
            $(".tryred").removeClass('tryred');
        };

        var customClick = function(){
            var r = parseInt($(this).attr('data-r'));
            var d = parseInt($(this).attr('data-d'));
            activeCustom(r, d);
        };

        var uniq = function(array){
            var temp = [];
            for (var i = 0; i < array.length; i++) {
                _total++;
                if(temp.indexOf(array[i]) == -1){
                    _count++;
                    temp.push(array[i]);
                }
            }
            //降序
            temp.sort();

            return temp;
        };

        var activeCustom = function(r, d){
            $("td").removeClass('active');
            $("td").removeClass('custom');
            $(".r" + r).addClass('active');
            $(".d" + d).addClass('active');
            s_area = $(".r" + r + ".d" + d).attr('data-area');
            $(".area" + s_area).addClass('active');
            $(".r" + r + ".d" + d).addClass('custom');
        }

        //_init();

        $(function(){
            $("td").on('click', customClick);

            $("#start").on('click', _beforeInit);
        });
    </script>
</body>
</html>
