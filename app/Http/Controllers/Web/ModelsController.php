<?php
/**
 * 前台模特列表控制器。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Controllers\Web;

use Illuminate\Support\Facades\DB;
use App\Http\Models\Common\LegModel;
use App\Http\Controllers\WebController;

class ModelsController extends WebController
{
    const RDS_KEY = 'model_lists';

    const LIFE_TIME = 86400;

    public function onGet()
    {
        $s_result = $this->getRedisData(static::RDS_KEY);
        if (false !== $s_result) {
            $a_models = json_decode($s_result, true);

            return $this->returnView('web.models', array('models' => $a_models, 'active' => 'models'));
        }
        $o_models = LegModel::where('status', '=', 1)->get();
        $o_album_models = DB::table('relation_album_models')->get();
        $a_album_models = array();
        foreach ($o_album_models as $album_mdl) {
            if (!isset($a_album_models[$album_mdl->model_id])) {
                $a_album_models[$album_mdl->model_id] = 1;

                continue;
            }
            $a_album_models[$album_mdl->model_id] += 1;
        }
        $a_models = $a_counts = array();
        foreach ($o_models as $mdl) {
            if (!isset($a_album_models[$mdl->id])) {
                continue;
            }
            $a_models[] = array(
                'id' => $mdl->id,
                'name' => $mdl->name,
                'count' => $a_album_models[$mdl->id]
            );
            $a_counts[] = $a_album_models[$mdl->id];
        }
        array_multisort($a_counts, SORT_DESC, $a_models);
        $this->setRedisData(static::RDS_KEY, json_encode($a_models), static::LIFE_TIME);

        return $this->returnView('web.models', array('models' => $a_models, 'active' => 'models'));
    }
}
