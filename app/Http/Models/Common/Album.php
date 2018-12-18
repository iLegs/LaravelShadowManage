<?php
/**
 * 专辑信息表模型。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Models\Common;

use App\Http\Models\Common\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    /**
     * 专辑信息表。
     *
     * @var string
     */
    protected $table = 'common_albums';

    protected $updated_at = false;

    protected $created_at = false;

    public $timestamps = false;

    public function getTags()
    {
        $o_r_tags = DB::table('relation_album_tags')->where('album_id', '=', $this->id)->select('tag_id')->get()->toArray();
        $a_tags = array();
        foreach ($o_r_tags as $tag) {
            $a_tags[] = $tag->tag_id;
        }
        $o_tags = Tag::whereIn('id', $a_tags)->where('status', '=', 1)->get();
        $a_rows = array();
        if ($o_tags->count() > 0) {
            foreach ($o_tags as $o_tag) {
                $a_rows[] = array(
                    'id' => $o_tag->id,
                    'title' => $o_tag->title
                );
            }
        }

        return $a_rows;
    }
}
