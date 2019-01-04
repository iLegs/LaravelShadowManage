<?php
/**
 * 专辑管理控制器。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Controllers\Shadow\Albums;

use Input;
use App\Http\Models\Common\Album;
use App\Http\Models\Common\LegLib;
use App\Http\Models\Common\LegModel;
use App\Http\Controllers\ShadowController;

class ManageController extends ShadowController
{
    const PAGE_SIZE = 6;

    public function any()
    {
        $i_page = Input::get('pageNum', 1);
        $i_lib = Input::get('lib', 0);
        $s_year = Input::get('year', 0);
        $s_year_eq = '=';
        if (0 == $s_year) {
            $s_year_eq = '!=';
        }
        $s_lib_eq = '=';
        if (0 == $i_lib) {
            $s_lib_eq = '!=';
        }
        if ($i_page == null) {
            $i_page = 1;
        }
        $i_count = Album::where('lib_id', $s_lib_eq, $i_lib)
            ->where('year', $s_year_eq, $s_year)
            ->where('status', '!=', 2)
            ->count();
        $o_albums = Album::where('lib_id', $s_lib_eq, $i_lib)
            ->where('year', $s_year_eq, $s_year)
            ->where('status', '!=', 2)
            ->orderBy('id', 'DESC')
            ->offset(($i_page - 1) * static::PAGE_SIZE)
            ->limit(static::PAGE_SIZE)
            ->get();
        $o_libs = LegLib::where('status', '=', 1)->get();
        $o_models = LegModel::where('status', '=', 1)->get();
        $a_row = array(
            'currentPage' => $i_page,
            'currentCount' => count($o_albums),
            'pageSize' => static::PAGE_SIZE,
            'totalCount' => $i_count,
            'albums' => $o_albums,
            'libs' => $o_libs,
            'libid' => $i_lib,
            'yearid' => $s_year,
            'pageNum' => $i_page
        );

        return $this->returnView('shadow.albums.manage', $a_row);
    }
}
