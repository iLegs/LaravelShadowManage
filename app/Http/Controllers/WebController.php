<?php
/**
 * 前台公用控制器。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Controllers;

use Log;
use Redis;
use Config;
use Response;
use Exception;
use Illuminate\Routing\Controller as BaseController;

class WebController extends BaseController
{
    /**
     * Redis 默认生命周期。
     */
    const LIFE_TIME = 86400;

    /**
     * redis 锁接口等待时间（秒）。
     */
    const LOCK_TIME = 2;

    /**
     * 接口休眠时间（微秒）。
     */
    const LOCK_WAIT_TIME = 10000;

    /**
     * 返回视图。
     * @param  object $view 视图文件路径
     * @param  array  $arr  返回参数
     * @return mixed
     */
    protected function returnView($view, $arr = array())
    {
        $arr['s'] = Config::get('app.static');

        return view($view)->with($arr);
    }

    /**
     * 获取 redis 数据。
     * @param  string  $key     键值
     * @param  bool    $is_lock 是否加锁
     * @param  string  $token   锁机制值
     * @return mixed
     */
    protected function getRedisData($key, $is_lock = 0)
    {
        //键存在，直接返回
        if (0 == $is_lock && !Redis::exists($key)) {
            //键不存在，且不加锁。直接返回false，查库。
            return false;
        }
        $s_value = uniqid();
        //加锁
        do {
            $s_lock_key = 'lock:' . $key;
            if (Redis::exists($key)) {
                if (Redis::exists($s_lock_key) && Redis::get($s_lock_key) == $s_value) {
                    Redis::del($s_lock_key);
                }

                return Redis::get($key);
            }
            //当键不存在时设置值，返回 true 或 false
            $b_key_locked = Redis::set($s_lock_key, $s_value, 'ex', static::LOCK_TIME, 'nx'); //ex 秒
            if (!$b_key_locked) {
                // 1秒 = 1000000 微秒
                //睡眠，降低抢锁频率，缓解redis压力
                usleep(static::LOCK_WAIT_TIME);
                continue;
            }

            return false;
        } while (!$b_key_locked);
    }

    /**
     * 设置redis。
     * @param string    $key       键名
     * @param mixed     $value     值
     * @param int       $life_time 过期时间
     */
    protected function setRedisData($key, $value, $life_time = self::LIFE_TIME)
    {
        $this->cleanRedisData($key);
        if (is_null($value) || '' == $value || !count($value)) {
            return false;
        }
        Redis::set($key, is_array($value) ? json_encode($value) : $value);
        if (static::LIFE_TIME >= 0) {
            Redis::expire($key, $life_time);
        }

        return true;
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

    protected function successJson($arr = array())
    {
        return Response::json($arr, 200);
    }

    protected function errorJson($msg, $code = 403)
    {
        return Response::json(array('msg' => $msg), $code);
    }
}
