<?php
/**
 * 后台公用控制器。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Controllers\Shadow;

use App\Http\Controllers\ShadowController;

class MainController extends ShadowController
{
    public function onGet()
    {
        return $this->returnView('shadow.main');
    }
}
