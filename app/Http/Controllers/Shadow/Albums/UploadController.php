<?php
/**
 * 新增专辑控制器。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Controllers\Shadow\Albums;

use Log;
use Input;
use Exception;
use App\Http\Models\Common\Album;
use App\Http\Controllers\ShadowController;

class UploadController extends ShadowController
{
    public function onGet()
    {
        $i_album = Input::get('aid', 0);
        $o_album = Album::find($i_album);
        if (!$o_album) {
            return $this->ajaxErrorJson('专辑信息获取失败！');
        }

        return $this->returnView('shadow.albums.upload_cover', array('album' => $o_album));
    }
}
