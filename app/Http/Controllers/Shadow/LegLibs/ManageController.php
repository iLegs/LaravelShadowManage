<?php
/**
 * 图库管理控制器。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Controllers\Shadow\LegLibs;

use Input;
use App\Http\Models\Common\LegLib;
use App\Http\Controllers\ShadowController;

class ManageController extends ShadowController
{
    public function any()
    {
        $i_page = Input::get('pageNum', 1);
        if ($i_page == null) {
            $i_page = 1;
        }
        $i_count = LegLib::where('status', '=', 1)->count();
        $o_libs = LegLib::where('status', '=', 1)->orderBy('id', 'DESC')
            ->offset(($i_page - 1) * static::PAGE_SIZE)
            ->limit(static::PAGE_SIZE)
            ->get();
        $a_row = array(
            'currentPage' => $i_page,
            'currentCount' => count($o_libs),
            'pageSize' => static::PAGE_SIZE,
            'totalCount' => $i_count,
            'leglibs' => $o_libs
        );

        return $this->returnView('shadow.leglibs.manage', $a_row);
    }
}
