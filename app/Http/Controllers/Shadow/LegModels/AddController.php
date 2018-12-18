<?php
/**
 * 新增模特控制器。
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

class AddController extends ShadowController
{
    public function onGet()
    {
        return $this->returnView('shadow.legmodels.add');
    }

    public function onPost()
    {
        try {
            $s_name = Input::get('name', '');
            $s_desc = Input::get('desc', '');
            if ('' == $s_name) {
                return $this->ajaxErrorJson('模特名称不能为空！');
            }
            $o_tag = new LegModel;
            $o_tag->name = $s_name;
            if ('' != $s_desc) {
                $o_tag->desc = $s_desc;
            }
            $o_tag->status = 1;
            $o_tag->save();

            return $this->ajaxSuccessJson('模特新增成功！');
        } catch (\Exception $e) {
            return $this->ajaxErrorJson('模特名称重复！');
        }
    }
}
