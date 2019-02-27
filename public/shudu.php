<?php
    $a_shudu = array(
        0 => array(9, 0, 0, 2, 0, 0, 0, 0, 0),
        1 => array(8, 0, 0, 3, 0, 0, 0, 0, 4),
        2 => array(0, 3, 5, 9, 0, 6, 0, 0, 0),
        3 => array(0, 0, 0, 0, 0, 0, 8, 7, 0),
        4 => array(0, 6, 0, 0, 0, 0, 0, 3, 0),
        5 => array(0, 5, 7, 0, 0, 0, 0, 0, 0),
        6 => array(0, 0, 0, 8, 0, 3, 6, 4, 0),
        7 => array(3, 0, 0, 0, 0, 2, 0, 0, 5),
        8 => array(0, 0, 0, 0, 0, 1, 0, 0, 2)
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
            background-color: red;
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
        .tryred {
            background-color: red;
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
        var sure = 1;

        var sleep = function (ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        };

        var clear = function(){
            $("td").removeClass('active');
            $("td").removeClass('custom');
        };

        var _getNumMayBeArr = function(r, d){
            var area = $(".r" + r + ".d" + d).attr('data-area');
            var tmp_arr = [];
            $(".hasv.area" + area).each(function(){
                tmp_arr.push(parseInt($(this).html()));
            });
            $(".hasv.d" + d).each(function(){
                tmp_arr.push(parseInt($(this).html()));
            });
            $(".hasv.r" + r).each(function(){
                tmp_arr.push(parseInt($(this).html()));
            });
            var maybe = [];
            tmp_arr = uniq(tmp_arr);
            tmp_arr.sort();
            for (var i = 1; i <= 9; i++) {
                //算法1:不包含法
                var index = tmp_arr.indexOf(i);
                if (-1 === index) {
                    maybe.push(i);
                }
            }
            //算法2:逐行逐列扫描

            return maybe;
        };

        async function _beforeInit(){
            for (var i = 1; i <= 9 ;i++) {
                _checkNumSpace(i);
                await sleep(200);
                clear();
            }
        };

        async function _checkNumSpace(num){
            $(".hasv").each(function(){
                var v = $(this).html();
                if (v == num) {
                    var r = $(this).attr('data-r');
                    var d = $(this).attr('data-d');
                    _activeHs(r, d);
                }
            });
            var space = [];
            for (var d = 0; d <= 8; d++) {
                for (var r = 0; r <= 8; r++) {
                    if ($(".r" + r + ".d" + d).hasClass('hasv') || $(".r" + r + ".d" + d).hasClass('active')) {
                        continue;
                    }
                    var aera = $(".r" + r + ".d" + d).attr('data-area');
                    if (typeof(space[aera]) === 'undefined') {
                        space[aera] = [];
                    }
                    space[aera].push('.r' + r + '.d' + d);
                }
            }
            for (var s in space) {
                if (space[s].length === 1) {
                    var r = $(space[s][0]).attr('data-r');
                    var d = $(space[s][0]).attr('data-d');
                    var maybe = [num];
                    bindOne(r, d, maybe);
                }
            }

            return space;
        };

        async function _activeHs(r, d){
            $(".r" + r).addClass('active');
            $(".d" + d).addClass('active');
            var aera = $(".r" + r + ".d" + d).attr('data-area');
            $(".area" + aera).addClass('active');
            $(".r" + r + ".d" + d).addClass('custom');
            await sleep(500);
        };

        var bindOne = function(r, d, maybe){
            if (try_flag === true && try_index !== -1) {
                $(".r" + r + ".d" + d).html(maybe[try_index]);
                $(".r" + r + ".d" + d).addClass('tryred');

                _init();
            } else {
                $(".r" + r + ".d" + d).html(maybe[0]);
                $(".r" + r + ".d" + d).addClass('sure' + sure);
            }
            $(".r" + r + ".d" + d).addClass('hasv');
            $(".r" + r + ".d" + d).removeClass('hasnone');
        };

        /**
         * 初始化加载。
         * @return mixed
         */
        async function _init(){
            var flag = false;
            for (var d = 0; d <= 8; d++) {
                for (var r = 0; r <= 8; r++) {
                    if ($(".r" + r + ".d" + d).hasClass('hasv')) {
                        continue;
                    }
                    activeCustom(r, d);
                    await sleep(100);
                    var maybe = _getNumMayBeArr(r, d);
                    console.log(maybe);
                    if (maybe.length === 1) {
                        bindOne(r, d, maybe);
                        await sleep(100);

                        flag = true;
                    }
                }
            }
            sure++;
            if (flag === true) {
                _init();
            }
            clear();
        };

        async function _cleanTry(){
            $(".tryred").html();
            $(".tryred").removeClass('tryred');
        };

        var _maybe = [];

        var try_flag = false;
        var try_key = -1;
        var try_index = -1;

        async function _randomNum(){
        };

        var customClick = function(){
            var r = parseInt($(this).attr('data-r'));
            var d = parseInt($(this).attr('data-d'));
            activeCustom(r, d);
        };

        var uniq = function(array){
            var temp = [];
            for (var i = 0; i < array.length; i++) {
                if(temp.indexOf(array[i]) == -1){
                    temp.push(array[i]);
                }
            }
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

            $("#start").on('click', _init);
        });
    </script>
</body>
</html>