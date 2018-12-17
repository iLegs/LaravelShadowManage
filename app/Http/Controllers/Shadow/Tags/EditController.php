<?php
/**
 * 编辑标签控制器。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Controllers\Shadow\Tags;

use Input;
use Exception;
use App\Http\Models\Common\Tag;
use App\Http\Controllers\ShadowController;

class EditController extends ShadowController
{
    public function onGet()
    {
        $i_tid = Input::get('tid', 0);
        $o_tag = Tag::find($i_tid);
        if (!$o_tag) {
            return $this->ajaxErrorJson('标签信息获取失败！');
        }

        return $this->returnView('shadow.tags.edit', array('tag' => $o_tag));
    }

    public function onPost()
    {
        try {
            $i_tid = Input::get('tid', 0);
            $o_tag = Tag::find($i_tid);
            if (!$o_tag) {
                return $this->ajaxErrorJson('标签信息获取失败！');
            }
            $s_title = Input::get('title', '');
            if ('' == $s_title) {
                return $this->ajaxErrorJson('标签不能为空！');
            }
            $o_tag->title = $s_title;
            $o_tag->save();

            return $this->ajaxSuccessJson('标签修改成功！');
        } catch (\Exception $e) {
            return $this->ajaxErrorJson('标签编辑失败！');
        }
    }
}
