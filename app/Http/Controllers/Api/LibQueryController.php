<?php
/**
 * 获取专辑名称列表控制器。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 api.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Controllers\Api;

use Log;
use Response;
use App\Http\Models\Common\LegLib;
use Illuminate\Routing\Controller as BaseController;

class LibQueryController extends BaseController
{
    public function onPost()
    {
        $a_rows = array();
        $o_leglibs = LegLib::where('status', '=', 1)
            ->orderBy('id', 'ASC')
            ->get();
        foreach ($o_leglibs as $lib) {
            $a_rows[] = array(
                'lib_id' => $lib->id,
                'title' => $lib->title
            );
        }
        $a_result = array(
            'code' => 1,
            'data' => $a_rows
        );

        return Response::json($a_result, 200);
    }
}
