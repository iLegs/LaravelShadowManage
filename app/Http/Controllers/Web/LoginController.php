<?php
/**
 * 前台登陆控制器。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Controllers\Web;

use App\Http\Controllers\WebController;

class LoginController extends WebController
{
    public function onGet()
    {
        return $this->returnView('web.login');
    }
}
