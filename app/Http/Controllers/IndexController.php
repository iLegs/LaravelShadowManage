<?php
/**
 * 前台首页控制器。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Controllers;

use Config;
use App\Http\Models\Common\Album;
use Illuminate\Routing\Controller as BaseController;

class IndexController extends BaseController
{
    public function onGet()
    {
        $o_albums = Album::where('status', '=', 1)->orderBy('id', 'DESC')->get();
        $arr = array(
            's' => Config::get('app.static'),
            'albums' => $o_albums
        );

        return view('index')->with($arr);
    }
}

