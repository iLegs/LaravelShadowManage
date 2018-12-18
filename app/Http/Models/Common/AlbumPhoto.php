<?php
/**
 * 专辑图片信息表模型。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Models\Common;

use Illuminate\Database\Eloquent\Model;

class AlbumPhoto extends Model
{
    /**
     * 专辑图片信息表。
     *
     * @var string
     */
    protected $table = 'common_album_photoes';

    protected $updated_at = false;

    protected $created_at = false;

    public $timestamps = false;
}
