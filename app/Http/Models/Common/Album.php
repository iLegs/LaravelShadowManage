<?php
/**
 * 专辑信息表模型。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Models\Common;

use Qiniu\Auth;
use App\Http\Models\Common\Tag;
use Illuminate\Support\Facades\DB;
use App\Http\Models\Common\LegModel;
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

    public function lib()
    {
        return $this->belongsTo('App\Http\Models\Common\LegLib', 'lib_id', 'id');
    }

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

    public function getModels()
    {
        $o_r_modelss = DB::table('relation_album_models')->where('album_id', '=', $this->id)->select('model_id')->get()->toArray();
        $a_models = array();
        foreach ($o_r_modelss as $model) {
            $a_models[] = $model->model_id;
        }
        $a_rows = array();
        $o_models = LegModel::whereIn('id', $a_models)->where('status', '=', 1)->get();
        if ($o_models->count() > 0) {
            foreach ($o_models as $o_model) {
                $a_rows[] = array(
                    'id' => $o_model->id,
                    'name' => $o_model->name
                );
            }
        }

        return $a_rows;
    }

    public function getCover()
    {
        if ('' == $this->cover) {
            return array(
                'preview' => '//s.imcn.vip/img/wz.png',
                'original' => '//s.imcn.vip/img/wz.png'
            );
        }
        $auth = new Auth(getenv('QINIU_AK'), getenv('QINIU_SK'));
        $baseUrl = 'http://' . getenv('QINIU_DOMAIN') . '/' . $this->cover;
        $a_result = array(
            'preview' => $auth->privateDownloadUrl($baseUrl . '-cover'),
            'original' => $auth->privateDownloadUrl($baseUrl)
        );

        return $a_result;
    }
}
