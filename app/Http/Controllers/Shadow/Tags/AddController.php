<?php
/**
 * 删除标签控制器。
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

class AddController extends ShadowController
{
    public function onGet()
    {
        return $this->returnView('shadow.tags.add');
    }

    public function onPost()
    {
        try {
            $s_title = Input::get('title', '');
            if ('' == $s_title) {
                return $this->ajaxErrorJson('标签不能为空！');
            }
            $o_tag = new Tag;
            $o_tag->title = $s_title;
            $o_tag->status = 1;
            $o_tag->save();

            return $this->ajaxSuccessJson('标签新增成功！');
        } catch (\Exception $e) {
            return $this->ajaxErrorJson('标签名称重复！');
        }
    }
}
