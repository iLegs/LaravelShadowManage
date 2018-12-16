<?php
/**
 * 后台公用控制器。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Controllers;

use Input;
use Redis;
use Session;
use Redirect;
use JWTAuth;
use App\Http\Models\Shadow\User;

class LoginController extends ShadowController
{
    const RDS_KEY = '_login';

    const RT_UNIT_TIME = 60;

    const RT_LIMIT = 8;

    public function onGet()
    {
        if ($this->user && $this->user->status == 1) {
            return Redirect::to('/shadow/main');
        }

        return $this->returnView('login');
    }

    public function onPost()
    {

        $s_account = Input::get('account', '');
        $s_pwd = Input::get('password', '');
        $s_captcha = Input::get('captcha', '');
        if ($s_captcha == '' || !app('captcha')->check($s_captcha)) {
            return $this->onFy('验证码输入错误！');
        } elseif ($s_account == '' || strlen($s_account) < 3 || $s_pwd == '' || strlen($s_pwd) < 6) {
            return $this->onFy('账号密码不能为空！');
        }
        $o_user = User::where('account', '=', $s_account)->first();
        if (!$o_user || !isset($o_user->password) || $o_user->status != 1 || !$this->checkPassword($s_pwd, $o_user->password)) {
            return $this->onFy('账号密码错误！');
        }
        $s_token = $o_user->generateToken();
        if (!$s_token) {
            return $this->onFy('身份令牌生成失败！');
        }
        Session::put('u', $s_token);

        return $this->successJson(array());
    }

    /**
     * 启用接口防御。
     * @param  string $msg 提示消息。
     * @return mixed
     */
    public function onFy($msg)
    {
        $b_flag = $this->defenseSys();
        if ($b_flag == false) {
            return $this->errorJson('接口请求频繁！~');
        }

        return $this->errorJson($msg);
    }
}
