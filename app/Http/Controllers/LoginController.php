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
use JWTAuth;
use Redirect;
use Qiniu\Auth;
use App\Http\Models\Shadow\User;
use App\Http\Models\Common\AlbumPhoto;

class LoginController extends ShadowController
{
    const RDS_KEY = '_login';

    const RT_UNIT_TIME = 60;

    const RT_LIMIT = 8;

    const RDS_BG_KEY = 'shadow_bg';

    public function onGet()
    {
        if ($this->user && $this->user->status == 1) {
            return Redirect::to('/shadow/main');
        }
        $s_bg = $this->getRedisData('bg');
        if (false === $s_bg) {
            $s_result = $this->getRedisData(static::RDS_BG_KEY);
            $a_results = array();
            if (false !== $s_result) {
                $a_results = json_decode($s_result, true);
            } else {
                $o_photoes = AlbumPhoto::where('status', '=', 1)->where('type', '=', 1)->get();
                if ($o_photoes->count() > 0) {
                    foreach ($o_photoes as $o_photo) {
                        $a_results[] = $o_photo->qn_key;
                    }
                    $this->setRedisData(static::RDS_BG_KEY, json_encode($a_results));
                }
            }
            $i = array_rand($a_results, 1);
            $auth = new Auth(getenv('QINIU_AK'), getenv('QINIU_SK'));
            $s_bg = $auth->privateDownloadUrl('http://' . getenv('QINIU_DOMAIN') . '/' . $a_results[$i] . '-crossrange_fhd');
            $this->setRedisData('bg', $s_bg, 60);
        }

        return $this->returnView('login', array('bg' => $s_bg));
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

        return $this->webSuccessJson();
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
