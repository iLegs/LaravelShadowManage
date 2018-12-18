<?php
/**
 * 编辑模特控制器。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Controllers\Shadow\LegModels;

use Input;
use Exception;
use App\Http\Models\Common\LegModel;
use App\Http\Controllers\ShadowController;

class EditController extends ShadowController
{
    public function onGet()
    {
        $i_lmid = Input::get('lmid', 0);
        if ($i_lmid <= 0) {
            return $this->ajaxErrorJson('模特信息获取失败！');
        }
        $o_legmodel = LegModel::find($i_lmid);
        if (!$o_legmodel) {
            return $this->ajaxErrorJson('模特信息获取失败！');
        }

        return $this->returnView('shadow.legmodels.edit', array('legmodel' => $o_legmodel));
    }

    public function onPost()
    {
        try {
            $i_lmid = Input::get('lmid', 0);
            if ($i_lmid <= 0) {
                return $this->ajaxErrorJson('模特信息获取失败！');
            }
            $o_legmodel = LegModel::find($i_lmid);
            if (!$o_legmodel) {
                return $this->ajaxErrorJson('模特信息获取失败！');
            }
            $s_name = Input::get('name', '');
            $s_desc = Input::get('desc', '');
            if ('' == $s_name) {
                return $this->ajaxErrorJson('模特名称不能为空！');
            }
            if ($s_name != $o_legmodel->name) {
                $o_legmodel->name = $s_name;
            }
            if ('' != $s_desc) {
                $o_legmodel->desc = $s_desc;
            }
            $o_legmodel->status = 1;
            $o_legmodel->save();

            return $this->ajaxSuccessJson('模特编辑成功！');
        } catch (\Exception $e) {
            return $this->ajaxErrorJson('模特名称重复！');
        }
    }
}
