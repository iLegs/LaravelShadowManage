<?php
/**
 * 前台标签列表控制器。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Controllers\Web;

use App\Http\Models\Common\Tag;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\WebController;

class TagsController extends WebController
{
    const RDS_KEY = 'tag_lists';

    const LIFE_TIME = 86400;

    public function onGet()
    {
        $s_result = $this->getRedisData(static::RDS_KEY);
        if (false !== $s_result) {
            $a_tags = json_decode($s_result, true);

            return $this->returnView('web.tags', array('tags' => $a_tags, 'acticve' => 'tags'));
        }
        $o_tags = Tag::where('status', '=', 1)->get();
        $o_album_tags = DB::table('relation_album_tags')->get();
        $a_album_tags = array();
        foreach ($o_album_tags as $album_tag) {
            if (!isset($a_album_tags[$album_tag->tag_id])) {
                $a_album_tags[$album_tag->tag_id] = 1;
            }
            $a_album_tags[$album_tag->tag_id] += 1;
        }
        $a_tags = $a_counts = array();
        foreach ($o_tags as $tag) {
            if (!isset($a_album_tags[$tag->id])) {
                continue;
            }
            $a_tags[] = array(
                'id' => $tag->id,
                'title' => $tag->title,
                'count' => $a_album_tags[$tag->id]
            );
            $a_counts[] = $a_album_tags[$tag->id];
        }
        array_multisort($a_counts, SORT_DESC, $a_tags);
        $this->setRedisData(static::RDS_KEY, json_encode($a_tags), static::LIFE_TIME);

        return $this->returnView('web.tags', array('tags' => $a_tags, 'acticve' => 'tags'));
    }
}
