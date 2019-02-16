<?php
/**
 * 模特专辑列表控制器。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Controllers\Web;

use Input;
use Redirect;
use Qiniu\Auth;
use App\Http\Models\Common\Tag;
use App\Http\Models\Common\Album;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\WebController;

class TagAlbumListController extends WebController
{
    const RDS_KEY = 'tag_albums_list:';

    const LIFE_TIME = 86400;

    const PAGE_SIZE = 12;

    public function onGet($key)
    {
        $o_tag = Tag::find($key);
        if (!$o_tag || 1 != $o_tag->status) {
            return Redirect::to('/');
        }
        $a_albums = $this->getAlbums($key);
        $a_albums = array_slice($a_albums, 0, static::PAGE_SIZE);
        $a_result['albums'] = $a_albums;
        $a_result['active'] = 'tags';
        $a_result['tag'] = $key;

        return $this->returnView('web.tag_album', $a_result);
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
        $s_key = static::RDS_KEY. $key;
        $s_result = $this->getRedisData($s_key);
        if (false !== $s_result) {
            $a_albums = json_decode($s_result, true);
        } else {
            $a_result = $a_albums = array();

            $o_ref_albums = DB::table('relation_album_tags')->where('tag_id', '=', $key)->get();
            if (!$o_ref_albums || !$o_ref_albums->count()) {
                return $a_albums;
            }
            $a_ids = array();
            foreach ($o_ref_albums as $ref) {
                $a_ids[] = $ref->album_id;
            }
            $a_ids = array_unique($a_ids);
            $o_albums = Album::whereIn('id', $a_ids)
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
