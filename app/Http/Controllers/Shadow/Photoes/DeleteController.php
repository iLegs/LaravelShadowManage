<?php
/**
 * 删除图片控制器。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Controllers\Shadow\Photoes;

use Input;
use App\Http\Models\Common\Photo;
use App\Http\Controllers\ShadowController;

class DeleteController extends ShadowController
{
    public function onPost()
    {
        $a_ids = Input::get('ids', array());
        if (!count($a_ids)) {
            return $this->ajaxErrorJson('编号输入错误！');
        }
        Photo::whereIn('id', $a_ids)->update(array('status' => 2));

        return $this->ajaxSuccessJson('删除成功！', 0);
    }
}
