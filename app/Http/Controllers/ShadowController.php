<?php
/**
 * 后台公用控制器。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Controllers;

use Log;
use Input;
use Redis;
use Config;
use JWTAuth;
use Session;
use Redirect;
use Exception;
use Response;
use App\Exceptions as Ex;
use Illuminate\Routing\Controller as BaseController;

class ShadowController extends BaseController
{
    /**
     * redis 防御指定控制器键名。
     */
    const RDS_KEY = '_szh_';

    /**
     * redis 生命周期（单位：秒）。
     */
    const RDS_LIFE_TIME = 86400;

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
     * nxid 单位时间（单位：秒）。
     */
    const NXID_UNIT_TIME = 600;

    /**
     * 限制单位时间内的nxid。
     */
    const NXID_LIMIT = 40;

    // 以 ip 为键， 2 分钟限制生成 nxid 40 个

    /**
     * ip 单位时间（单位：秒）。
     */
    const IP_UNIT_TIME = 600;

    /**
     * 单位时间内ip 的请求次数。
     */
    const IP_LIMIT = 5;

    // 以 nxid 为键， 10 分钟限制 ip 变换 5 个

    /**
     * 请求单位时间。
     */
    const RT_UNIT_TIME = 60;

    /**
     * 请求限制次数。
     */
    const RT_LIMIT = 6;

    // 2 分钟限制请求 8 次

    /**
     * 后台节点权限编码。
     */
    const CODE = '0-0-0';

    /**
     * 分页；每页展示数据条数。
     */
    const PAGE_SIZE = 20;

    /**
     * 当前登录用户对象。
     * @var mixed
     */
    protected $user = false;

    protected static $a_buckets = array(
        'resource' => 'http://leg.imcn.vip/',
        'lolita' => 'http://ilolita.imcn.vip/'
    );

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $o_user = $request->get('user');
            if ((!$o_user || !isset($o_user->id)) && static::CODE != '0-0-0') {
                $this->removeLoginUser();

                throw new Ex\TokenMissException();
            }
            $this->user = $o_user;
            $code = static::CODE;
            if ($code != '0-0-0' && $o_user->id > 1000) {
                $b_bool = $this->checkCode($code);
                if ($b_bool == false) {
                    throw new Exception("请求失败!", 1003);
                }
            }
            return $next($request);
        });
    }

    /**
     * 移除当前登录用户的session和token。
     * @return mixed
     */
    protected function removeLoginUser()
    {
        try {
            if (Session::has('u')) {
                Session::remove('u');
            }
            if (JWTAuth::getToken() !== false) {
                JWTAuth::invalidate();
            }

            return true;
        } catch (\Exception $e) {
            Log::info($e->getMessage());

            return false;
        }
    }

    /**
     * 生成密码。
     * @return string
     */
    protected function generatePassword($pwd = '')
    {
        if ($pwd == '' || $pwd == null || strlen($pwd) < 6) {
            throw new Ex\PwdNullException();
        }

        return password_hash($pwd, PASSWORD_BCRYPT, array('cost' => 12));
    }

    /**
     * 检查密码是否一致。
     * @param  string $pwd  密码明文
     * @param  string $hash 加密后的密码
     * @return bool
     */
    protected function checkPassword($pwd = '', $hash = '')
    {
        if ($pwd == '' || $hash == '') {
            throw new Ex\PwdNullException();
        }

        return password_verify($pwd, $hash);
    }

    /**
     * ajax 请求错误返回结果。
     *
     * @param  string  $msg  错误提示
     * @param  integer $code http 状态码
     * @return mixed
     */
    protected function ajaxErrorJson($msg, $code = 403)
    {
        $a_result = array(
            "message" => $msg,
            "statusCode" => $code
        );

        return Response::json($a_result, 200);
    }

    protected function errorJson($msg, $code = 403)
    {
        return Response::json(array('msg' => $msg), $code);
    }

    /**
     * ajax 请求成功返回结果。
     * @param  array $arr 数组
     * @return mixed
     */
    protected function ajaxSuccessJson($msg = '', $type = 1, $nav_id = '')
    {
        $a_result = array(
            "callbackType" => $type == 0 ? "forward" : "closeCurrent",
            "confirmMsg" => "",
            "forwardUrl" => "",
            "message" => $msg,
            "navTabId" => $nav_id,
            "rel" => '',
            "statusCode" => 200
        );

        return Response::json($a_result, 200);
    }

    /**
     * ajax 请求成功返回结果。
     * @param  array $arr 数组
     * @return mixed
     */
    protected function webSuccessJson($arr = array())
    {
        return Response::json($arr, 200);
    }

    /**
     * 返回视图。
     * @param  object $view 视图文件路径
     * @param  array  $arr  返回参数
     * @return mixed
     */
    protected function returnView($view, $arr = array())
    {
        $arr['s'] = Config::get('app.static');
        $arr['user'] = $this->user;

        return view($view)->with($arr);
    }

    /**
     * redis 智能防御系统。
     * @return bool
     */
    public function defenseSys()
    {
        if (!isset($_COOKIE['nxid'])) {
            Log::info('nxid不存在！');
            return false;
        }
        //以ip为键，判断单位时间内生成的nxid
        $s_ip = $_SERVER["REMOTE_ADDR"];
        $s_ip_key = $s_ip . static::RDS_KEY;
        $s_nxid = $_COOKIE['nxid'];
        $a_nxid_row = array(
            'lt' => time(),
            'nxid' => $s_nxid
        );
        $s_nxid_key = $s_nxid . static::RDS_KEY;
        //以nxid为键，判断单位时间内的请求
        if (Redis::exists($s_nxid_key)) {
            $b_rt_flag = true;
            $i_rt_len = Redis::lLen($s_nxid_key);
            if (static::RT_LIMIT < $i_rt_len) {
                for ($irt =0; $irt < $i_rt_len; $irt++) {
                    $a_rt_vals = json_decode(Redis::lindex($s_nxid_key, $irt), true);
                    if (!isset($a_rt_vals['lt']) || (time() > (static::RT_UNIT_TIME + $a_rt_vals['lt']))) {
                        break;
                    } elseif (static::RT_LIMIT < ($irt + 1)) {
                        $b_rt_flag = false;
                        break;
                    }
                }
            }
            if ($b_rt_flag == false) {
                return false;
            }
        }
        //判断是否存在，不存在则写入数据
        if (Redis::exists($s_ip_key) && static::NXID_LIMIT < Redis::lLen($s_ip_key)) {
            $i_ip_len = Redis::lLen($s_ip_key);
            for ($i = ($i_ip_len - 1); $i < $i_ip_len && 0 < $i; $i--) {
                $a_vals = json_decode(Redis::lindex($s_ip_key, $i), true);
                if (!isset($a_vals['lt'])) {
                    Redis::rpop($s_ip_key);

                    continue;
                } elseif ((time() - static::NXID_UNIT_TIME) < $a_vals['lt']) {
                    break;
                } elseif ((time() - static::NXID_UNIT_TIME) > $a_vals['lt']) {
                    Redis::rpop($s_ip_key);

                    continue;
                }
            }
            $a_nxids = array();
            $i_new_len = Redis::lLen($s_ip_key);
            if (static::NXID_LIMIT < $i_new_len) {
                for ($i = 0; $i < $i_new_len; $i++) {
                    $a_values = json_decode(Redis::lindex($s_ip_key, $i), true);
                    if (!isset($a_values['lt']) || !isset($a_values['nxid'])) {
                        continue;
                    }
                    $a_nxids[$a_values['nxid']] = $a_values['lt'];
                }
            }
            if (static::NXID_LIMIT < count($a_nxids)) {
                Log::info('ip:' . $s_ip . '生成 nxid 数量：' . count($a_nxids) . '请求频繁！');

                return false;
            }
            Redis::lpush($s_ip_key, json_encode($a_nxid_row));
        } else {
            Redis::lpush($s_ip_key, json_encode($a_nxid_row));
            Redis::expire($s_ip_key, static::RDS_LIFE_TIME);
        }
        //以nxid为键，判断单位时间内生成的ip
        $a_ip_row = array(
            'lt' => time(),
            'ip' => $s_ip
        );
        if (Redis::exists($s_nxid_key) && static::IP_LIMIT < Redis::lLen($s_nxid_key)) {
            $i_nx_len = Redis::lLen($s_nxid_key);
            for ($ii = ($i_nx_len - 1); $ii < $i_nx_len && 0 < $ii; $ii--) {
                $a_ip_vals = json_decode(Redis::lindex($s_nxid_key, $ii), true);
                if (!isset($a_ip_vals['lt'])) {
                    Redis::rpop($s_nxid_key);

                    continue;
                } elseif ((time() - static::IP_UNIT_TIME) < $a_ip_vals['lt']) {
                    break;
                } elseif ((time() - static::IP_UNIT_TIME) > $a_ip_vals['lt']) {
                    Redis::rpop($s_nxid_key);

                    continue;
                }
            }
            $a_ips = array();
            $i_new_ip_len = Redis::lLen($s_nxid_key);
            if (static::IP_LIMIT < $i_new_ip_len) {
                for ($iis = 0; $iis < $i_new_ip_len; $iis++) {
                    $a_ip_values = json_decode(Redis::lindex($s_nxid_key, $iis), true);
                    if (!isset($a_ip_values['lt']) || !isset($a_ip_values['ip'])) {
                        continue;
                    }
                    $a_ips[$a_ip_values['ip']] = $a_ip_values['ip'];
                }
            }
            if (static::IP_LIMIT < count($a_ips)) {
                Log::info('nxid:' . $s_nxid . 'ip:'  . $s_ip .  '生成 ip 数量：' . count($a_nxids) . '请求频繁！');

                return false;
            }
            Redis::lpush($s_nxid_key, json_encode($a_ip_row));
        } else {
            Redis::lpush($s_nxid_key, json_encode($a_ip_row));
            Redis::expire($s_nxid_key, static::RDS_LIFE_TIME);
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

    protected function getAlbumDomain($bucket = '')
    {
        if (isset(static::$a_buckets[$bucket]) && '' != $bucket) {
            return static::$a_buckets[$bucket];
        }

        return static::$a_buckets['resource'];
    }
}
