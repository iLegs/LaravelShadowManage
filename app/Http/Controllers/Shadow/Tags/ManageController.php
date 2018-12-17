<?php
/**
 * 标签管理控制器。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Controllers\Shadow\Tags;

use Input;
use App\Http\Models\Common\Tag;
use App\Http\Controllers\ShadowController;

class ManageController extends ShadowController
{
    const ACTIVE = 'tags_m';

    public function any()
    {
        $i_page = Input::get('pageNum', 1);
        if ($i_page == null) {
            $i_page = 1;
        }
        $i_count = Tag::where('status', '=', 1)->count();
        $o_tags = Tag::where('status', '=', 1)->orderBy('id', 'DESC')
            ->offset(($i_page - 1) * static::PAGE_SIZE)
            ->limit(static::PAGE_SIZE)
            ->get();
        $a_row = array(
            'currentPage' => $i_page,
            'currentCount' => count($o_tags),
            'pageSize' => static::PAGE_SIZE,
            'totalCount' => $i_count,
            'tags' => $o_tags
        );

        return $this->returnView('shadow.tags.manage', $a_row);
    }
}
