<?php
/**
 * 后台用户身份令牌验证中间件。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Request;
use Response;
use Redirect;
use App\Exceptions as Ex;
use Illuminate\Support\Facades\Session;

class ShadowMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            $o_user = false;
            $s_url = Request::getRequestUri();
            $url_login_flag = strstr('/shadow/login', $s_url);
            if (Session::has('u') && Session::get('u') !== null) {
                $o_user = JWTAuth::toUser(Session::get('u'));
            }
            if ((!$o_user || !isset($o_user->status) || $o_user->status != 1) && $url_login_flag == false) {
                if (JWTAuth::getToken() !== false) {
                    JWTAuth::invalidate(JWTAuth::getToken());
                }
                if (Session::has('u')) {
                    Session::remove('u');
                }

                return Response::json(array("message" => "Token身份令牌失效～", "statusCode" => 301), 301);
            }
            $request->attributes->add(['user' => $o_user]);

            //继续向下执行
            return $next($request);
        } catch (\Exception $e) {
            if (Session::has('u')) {
                Session::remove('u');
            }

            return Response::json(array("message" => "Token身份令牌失效～", "statusCode" => 301), 301);
        }
    }
}
