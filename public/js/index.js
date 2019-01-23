/**
 * 首页js。
 *
 * @author    fairyin <fairyin@126.cn>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

$(function(){
    if (!$(".web_index").length) return;

    if ("object" !== typeof _index) _index = {};

    _index.loadImage = function(){
        $("img.lazyload").lazyload();
    };

    _index.loadImage();
});
