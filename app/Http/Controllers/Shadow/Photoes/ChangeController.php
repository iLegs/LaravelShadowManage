<?php
/**
 * 改变图片属性控制器。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Controllers\Shadow\Photoes;

use Input;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ShadowController;

class ChangeController extends ShadowController
{
    public function horizontal()
    {
        //横
        $a_ids = Input::get('ids', array());
        if (!count($a_ids)) {
            return $this->ajaxErrorJson('编号输入错误！');
        }
        DB::table('common_album_photoes')->whereIn('id', $a_ids)->update(array('type' => 1));

        return $this->ajaxSuccessJson('操作成功！', 0);
    }

    public function vertical()
    {
        //竖
        $a_ids = Input::get('ids', array());
        if (!count($a_ids)) {
            return $this->ajaxErrorJson('编号输入错误！');
        }
        DB::table('common_album_photoes')->whereIn('id', $a_ids)->update(array('type' => 0));

        return $this->ajaxSuccessJson('操作成功！', 0);
    }
}
