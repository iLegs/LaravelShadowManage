<?php
/**
 * 前台首页控制器。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Controllers;

use Input;
use Qiniu\Auth;
use App\Http\Models\Common\Album;

class IndexController extends WebController
{
    const LIFE_TIME = 1200;

    const PAGE_SIZE = 8;

    /**
     * Redis key 专辑列表。
     */
    const RDS_KEY = 'albums_list';

    public function onGet()
    {
        return $this->returnView('index');
    }

    public function onPost()
    {
        $i_page = Input::get('page', 1);
        $s_result = $this->getRedisData(static::RDS_KEY);
        $a_albums = array();
        if (false !== $s_result) {
            $a_albums = json_decode($s_result, true);
        } else {
            $o_albums = Album::where('status', '=', 1)->orderBy('id', 'DESC')->get();
            $o_auth = new Auth(getenv('QINIU_AK'), getenv('QINIU_SK'));
            if ($o_albums && $o_albums->count() > 0) {
                foreach ($o_albums as $o_album) {
                    $s_url = 'http://' . getenv('QINIU_DOMAIN') . '/' . $o_album->cover;
                    $a_albums[] = array(
                        'id' => $o_album->id,
                        'title' => $o_album->title,
                        'cover' => $o_auth->privateDownloadUrl($s_url . '-mobile_cover')
                    );
                }
                $this->setRedisData(static::RDS_KEY, json_encode($a_albums), static::LIFE_TIME);
            }
        }
        $i_count = count($a_albums);
        $i_start = ($i_page - 1) * static::PAGE_SIZE;
        $a_rows = array_slice($a_albums, $i_start, static::PAGE_SIZE);
        $a_result = array(
            'page' => $i_page,
            'current_count' => count($a_rows),
            'rows' => $a_rows
        );

        return $this->successJson($a_result);
    }
}

