<?php
/**
 * 后台退出登录控制器。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Controllers;

use Redirect;

class LogoutController extends ShadowController
{
    public function onGet()
    {
        try {
            $this->removeLoginUser();
        } catch (\Exception $e) {
            return Redirect::to('/shadow/login');
        }

        return Redirect::to('/shadow/login');
    }
}
