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
use Redis;
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

    /**
     * 删除 redis 指定的 key。
     * @param  string $key 键名
     * @return bool
     */
    protected function cleanRedisData($key, $flag = 0)
    {
        try {
            //批量删除
            if (1 === $flag) {
                $a_keys = Redis::keys($key . '*');
                if (!count($a_keys)) {
                    return false;
                }
                Redis::del($a_keys);

                return true;
            }
            //普通删除
            if (Redis::exists($key)) {
                Redis::del($key);

                return true;
            }

            return false;
        } catch (Exception $e) {
            Log::info('redis del erroor:' . $key . 'msg:' . $e->getMessage());

            return false;
        }
    }
}
