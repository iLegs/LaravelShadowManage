<?php
/**
 * 图片信息表模型。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Models\Common;

use Qiniu\Auth;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    /**
     * 图片信息表。
     *
     * @var string
     */
    protected $table = 'common_album_photoes';

    protected $updated_at = false;

    protected $created_at = false;

    public $timestamps = false;

    public function album()
    {
        return $this->belongsTo('App\Http\Models\Common\Album', 'album_id', 'id');
    }

    public function getUrl()
    {
        $auth = new Auth(getenv('QINIU_AK'), getenv('QINIU_SK'));
        $baseUrl = 'http://' . getenv('QINIU_DOMAIN') . '/' . $this->qn_key;

        return $auth->privateDownloadUrl($baseUrl . '-preview');
    }
}
