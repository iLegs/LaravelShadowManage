<?php
/**
 * 删除专辑控制器。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Controllers\Shadow\Albums;

use Input;
use App\Http\Models\Common\Album;
use App\Http\Controllers\ShadowController;

class DeleteController extends ShadowController
{
    public function onPost()
    {
        $a_ids = Input::get('ids', array());
        if (!count($a_ids)) {
            return $this->ajaxErrorJson('编号输入错误！');
        }
        Album::whereIn('id', $a_ids)->update(array('status' => 2));

        return $this->ajaxSuccessJson('删除成功！');
    }
}
