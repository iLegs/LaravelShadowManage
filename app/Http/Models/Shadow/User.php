<?php
/**
 * 订单信息表模型。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Models\Shadow;

use JWTAuth;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * 后台管理员信息表。
     *
     * @var string
     */
    protected $table = 'shadow_users';

    protected $updated_at = false;

    protected $created_at = false;

    public $timestamps = false;

    /**
     * 生成用户身份令牌。
     * @return string
     */
    public function generateToken()
    {
        return JWTAuth::fromUser($this);
    }
}
