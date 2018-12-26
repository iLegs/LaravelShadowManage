<?php
/**
 * 专辑图片列表控制器。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Controllers;

use Redirect;
use Qiniu\Auth;
use App\Http\Models\Common\Album;
use App\Http\Models\Common\AlbumPhoto;

class AlbumDetailController extends WebController
{
    const RDS_KEY = 'photoes:album_';

    const LIFE_TIME = 1200;

    public function onGet($albumid)
    {
        $a_result = array();
        $o_album = Album::find($albumid);
        if (!$o_album) {
            return Redirect::to('/');
        }
        $a_result['album'] = $o_album;
        $s_result = $this->getRedisData(static::RDS_KEY . $o_album->id);
        if (false !== $s_result) {
            $a_result['photoes'] = json_decode($s_result, true);

            return $this->returnView('album_detail_photoes', $a_result);
        }
        $o_photoes = AlbumPhoto::where('status', '=', 1)
            ->where('album_id', '=', $o_album->id)
            ->get();
        if (!$o_photoes || $o_photoes->count() <= 0) {
            return Redirect::to('/');
        }
        $a_photoes = array();
        $o_auth = new Auth(getenv('QINIU_AK'), getenv('QINIU_SK'));
        foreach ($o_photoes as $o_photo) {
            $s_url = 'http://' . getenv('QINIU_DOMAIN') . '/' . $o_photo->qn_key;
            $s_str = 'vertical_hd';
            if (1 == $o_photo->type) {
                $s_str = 'crossrange_hd';
            }
            $a_photoes[] = array(
                'id' => $o_photo->id,
                'preview' => $o_auth->privateDownloadUrl($s_url . '-square_preview'),
                'original' => $o_auth->privateDownloadUrl($s_url . '-' . $s_str)
            );
        }
        $this->setRedisData(static::RDS_KEY . $o_album->id, json_encode($a_photoes), static::LIFE_TIME);
        $a_result['photoes'] = $a_photoes;

        return $this->returnView('album_detail_photoes', $a_result);
    }
}
