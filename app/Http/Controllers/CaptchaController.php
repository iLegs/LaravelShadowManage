<?php
/**
 * 生成验证码控制器。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Controllers;

class CaptchaController extends ShadowController
{
    const RDS_KEY = '_captcha';

    public function onGet()
    {
        $b_flag = $this->defenseSys();
        if (!$b_flag) {
            return $this->errorJson('验证码刷新过于频繁！');
        }

        return captcha();
    }
}
