<?php
/**
 * 图库编辑控制器。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Controllers\Shadow\LegLibs;

use Input;
use App\Http\Models\Common\LegLib;
use App\Http\Controllers\ShadowController;

class EditController extends ShadowController
{
    public function onGet()
    {
        $i_libid = Input::get('libid', 0);
        if ($i_libid <= 0) {
            dd('x');
            return $this->ajaxErrorJson('图库信息获取失败！');
        }
        $o_lib = LegLib::find($i_libid);
        if (!$o_lib || 1 != $o_lib->status) {
            dd('y');

            return $this->ajaxErrorJson('图库信息获取失败！');
        }

        return $this->returnView('shadow.leglibs.edit', array('lib' => $o_lib));
    }

    public function onPost()
    {
        try {
            $i_libid = Input::get('libid', 0);
            if ($i_libid <= 0) {
                return $this->ajaxErrorJson('图库信息获取失败！');
            }
            $o_lib = LegLib::find($i_libid);
            if (!$o_lib || 1 != $o_lib->status) {
                return $this->ajaxErrorJson('图库信息获取失败！');
            }
            $s_title = Input::get('title', '');
            $s_desc = Input::get('desc', '');
            if ('' == $s_title) {
                return $this->ajaxErrorJson('名称不能为空！');
            }
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
