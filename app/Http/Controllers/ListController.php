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
use Redirect;
use Qiniu\Auth;
use App\Http\Models\Common\Album;
use App\Http\Models\Common\LegLib;
use Illuminate\Support\Facades\DB;

class ListController extends WebController
{
    const PAGE_SIZE = 12;

    const RDS_KEY = 'list';

    public function onGet($key)
    {
        $o_lib = LegLib::where('status', '=', 1)->where('url', '=', $key)->first();
        if (!$o_lib) {
            return Redirect::to('/');
        }
        $a_albums = $this->getAlbums($key);
        $a_albums = array_slice($a_albums, 0, static::PAGE_SIZE);
        $a_result['albums'] = $a_albums;
        $a_result['active'] = $key;
        $a_result['seo'] = array(
            'title' => $o_lib->title,
            'keywords' => $o_lib->title,
            'description' => $o_lib->desc
        );

        return $this->returnView('list', $a_result);
    }

    public function onPost($key)
    {
        $i_page = Input::get('page', 1);
        $a_albums = $this->getAlbums($key);
        $i_total_count = count($a_albums);
        $a_albums = array_slice($a_albums, ($i_page - 1) * static::PAGE_SIZE, static::PAGE_SIZE);
        $a_result = array(
            'total' => $i_total_count,
            'page' => $i_page,
            'current_count' => count($a_albums),
            'rows' => $a_albums
        );

        return $this->successJson($a_result);
    }

    private function getAlbums($key)
    {
        $a_albums = array();
        $s_key = static::RDS_KEY . '_' . $key;
        $s_result = $this->getRedisData($s_key);
        if (false !== $s_result) {
            $a_albums = json_decode($s_result, true);
        } else {
            $a_result = $a_albums = array();
            $o_lib = LegLib::where('status', '=', 1)->where('url', '=', $key)->first();
            if (!$o_lib) {
                return Redirect::to('/');
            }
            $o_albums = Album::where('lib_id', '=', $o_lib->id)
                ->where('status', '=', 1)
                ->orderBy('date', 'DESC')
                ->get();
            if ($o_albums->count()) {
                $o_auth = new Auth(getenv('QINIU_AK'), getenv('QINIU_SK'));
                foreach ($o_albums as $album) {
                    $s_url = $this->getAlbumDomain() . $album->cover;
                    $a_albums[] = array(
                        'id' => $album->id,
                        'title' => $album->title,
                        'cover' => $o_auth->privateDownloadUrl($s_url . '-mobile_cover', static::LIFE_TIME * 3)
                    );
                }
                $this->setRedisData($s_key, json_encode($a_albums), static::LIFE_TIME);
            }
        }

        return $a_albums;
    }
}
