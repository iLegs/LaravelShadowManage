<?php
/**
 * 图片管理控制器。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Controllers\Shadow\Photoes;

use Input;
use App\Http\Models\Common\Album;
use App\Http\Models\Common\AlbumPhoto;
use App\Http\Controllers\ShadowController;

class ManageController extends ShadowController
{
    public function any()
    {
        $i_page = Input::get('pageNum', 1);
        if ($i_page == null) {
            $i_page = 1;
        }
        $i_album = Input::get('albumid', 0);
        $s_album_eq = '=';
        if (0 == $i_album) {
            $s_album_eq = '!=';
        }
        $i_type = Input::get('type', -1);
        $s_type_eq = '=';
        if (-1 == $i_type) {
            $s_type_eq = '!=';
        }
        $i_count = AlbumPhoto::where('status', '=', 1)
            ->where('album_id', $s_album_eq, $i_album)
            ->where('type', $s_type_eq, $i_type)
            ->count();
        $o_photoes = AlbumPhoto::where('status', '=', 1)
            ->where('type', $s_type_eq, $i_type)
            ->where('album_id', $s_album_eq, $i_album)
            ->orderBy('id', 'DESC')
            ->offset(($i_page - 1) * static::PAGE_SIZE)
            ->limit(static::PAGE_SIZE)
            ->get();
        $o_albums = Album::where('status', '=', 1)->get();
        $a_row = array(
            'currentPage' => $i_page,
            'currentCount' => count($o_photoes),
            'pageSize' => static::PAGE_SIZE,
            'totalCount' => $i_count,
            'photoes' => $o_photoes,
            'albums' => $o_albums,
            'albumid' => $i_album,
            'type' => $i_type,
            'pageNum' => $i_page
        );

        return $this->returnView('shadow.photoes.manage', $a_row);
    }
}
