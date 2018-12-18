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

class AddController extends ShadowController
{
    public function onGet()
    {
        return $this->returnView('shadow.leglibs.add');
    }

    public function onPost()
    {
        try {
            $s_title = Input::get('title', '');
            $s_desc = Input::get('desc', '');
            if ('' == $s_title) {
                return $this->ajaxErrorJson('名称不能为空！');
            }
            $o_lib = new LegLib;
            $o_lib->title = $s_title;
            if ('' != $s_desc) {
                $o_lib->desc = $s_desc;
            }
            $o_lib->status = 1;
            $o_lib->save();

            return $this->ajaxSuccessJson('新增成功！');
        } catch (\Exception $e) {
            return $this->ajaxErrorJson('名称重复！');
        }
    }
}
