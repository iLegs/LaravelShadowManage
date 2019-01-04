<?php
/**
 * 图库信息表模型。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Models\Common;

use Illuminate\Database\Eloquent\Model;

class LegLib extends Model
{
    /**
     * 图库信息表。
     *
     * @var string
     */
    protected $table = 'common_libs';

    protected $updated_at = false;

    protected $created_at = false;

    public $timestamps = false;

    public function getAlbumsCount()
    {
        return Album::where('status', '=', 1)->where('lib_id', '=', $this->id)->count();
    }
}
