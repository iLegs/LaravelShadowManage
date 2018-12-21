<?php
/**
 * 脚本任务公用控制器。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Controllers\Cron;

use Input;
use Exception;
use Illuminate\Routing\Controller as BaseController;

class CronController extends BaseController
{
    public function __construct()
    {
        $s_pass = Input::get('pass', '');
        if ('' == $s_pass || getenv('CRON_SECRET') != $s_pass) {
            throw new Exception("秘钥验证失败！");
        }
    }
}
