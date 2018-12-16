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
     * 当前页面url。
     */
    const URL = '/shadow/main';

    /**
     * 当前登录用户对象。
     * @var mixed
     */
    protected $user = false;

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
    protected function errorJson($msg, $code = 403)
    {
        return Response::json(array('error' => true, 'msg' => $msg), $code);
    }

    /**
     * ajax 请求成功返回结果。
     * @param  array $arr 数组
     * @return mixed
     */
    protected function successJson($arr)
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
        $arr['tops'] = array();
        $i_active = Input::get('active', 0);
        $arr['active'] = $i_active;
        $arr['thisurl'] = static::URL;

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
}
