<?php
/**
 * 前台公用控制器。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Controllers;

use App\Http\Models\Common\Album;

class SitemapController extends WebController
{
    const LIFE_TIME = 86400;

    public function onGet()
    {
        $s_key = 'sitemap_key';
        $s_result = $this->getRedisData($s_key);
        $a_albums = array();
        $s_update_date = date('Y-m-d H:i:s', time());
        if ($s_result !== false) {
            $a_result = json_decode($s_result, true);
            $a_albums = $a_result['rows'];
            $s_update_date = $a_result['date'];
        } else {
            $o_albums = Album::where('status', '=', 1)->orderBy('year', 'DESC')->get();
            if ($o_albums->count()) {
                foreach ($o_albums as $o_album) {
                    $a_albums[] = $o_album->id;
                }
            }
            $a_result = array(
                'rows' => $a_albums,
                'date' => $s_update_date
            );
            $this->setRedisData($s_key, json_encode($a_result), (static::LIFE_TIME * 7));
        }

        return response()->view('sitemap', [
            'albums' => $a_albums,
            'add_time' => $s_update_date
        ])->header('Content-Type', 'text/xml');
    }
}
