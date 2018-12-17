<?php
/**
 * 模特信息表模型。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Models\Common;

use Illuminate\Database\Eloquent\Model;

class LegModel extends Model
{
    /**
     * 标签信息表。
     *
     * @var string
     */
    protected $table = 'common_models';

    protected $updated_at = false;

    protected $created_at = false;

    public $timestamps = false;
}
