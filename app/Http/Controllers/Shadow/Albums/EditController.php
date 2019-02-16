<?php
/**
 * 编辑专辑控制器。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Controllers\Shadow\Albums;

use Log;
use Input;
use Exception;
use App\Http\Models\Common\Tag;
use App\Http\Models\Common\Album;
use App\Http\Models\Common\LegLib;
use Illuminate\Support\Facades\DB;
use App\Http\Models\Common\LegModel;
use App\Http\Controllers\ShadowController;

class EditController extends ShadowController
{
    public function onGet()
    {
        $o_tags = Tag::where('status', '=', 1)->get();
        $o_legmodels = LegModel::where('status', '=', 1)->get();
        $o_libs = LegLib::where('status', '=', 1)->get();
        $o_album = Album::find(Input::get('aid'));
        $a_result = array(
            'tags' => $o_tags,
            'legmodels' => $o_legmodels,
            'libs' => $o_libs,
            'next_day' => date('Y-m-d', time()),
            'album' => $o_album
        );

        return $this->returnView('shadow.albums.edit', $a_result);
    }

    public function onPost()
    {
        try {
            DB::beginTransaction();
            $i_aid = Input::get('aid', 0);
            $s_title = Input::get('title', '');
            $s_desc = Input::get('desc', '');
            $a_tags = Input::get('tag', array());
            $a_models = Input::get('mdl', array());
            $i_lib = Input::get('lib', 0);
            $s_date = Input::get('date', '');
            $i_status = Input::get('status', -1);
            $s_bucket = Input::get('bucket', 'resource');
            if ('' == $s_title) {
                return $this->ajaxErrorJson('请输入专辑名称！');
            } elseif (!count($a_tags)) {
                return $this->ajaxErrorJson('请选择标签！');
            } elseif (!count($a_models)) {
                return $this->ajaxErrorJson('请选择模特！');
            } elseif ($i_lib <= 0) {
                return $this->ajaxErrorJson('请选择图库！');
            } elseif ('' == $s_date) {
                return $this->ajaxErrorJson('请选择专辑发行日期！');
            } elseif (!in_array($i_status, array(0, 1, 2))) {
                return $this->ajaxErrorJson('专辑状态错误！');
            }
            $o_lib = LegLib::find($i_lib);
            if (!$o_lib || 1 != $o_lib->status) {
                return $this->ajaxErrorJson('图库数据异常！');
            }
            $o_album = Album::find($i_aid);
            if (!$o_album) {
                return $this->ajaxErrorJson('专辑数据异常！');
            }
            $o_album->title = $s_title;
            if ('' != $s_desc) {
                $o_album->desc = $s_desc;
            }
            $o_album->date = $s_date;
            $o_album->year = date('Y', strtotime($s_date));
            $o_album->lib_id = $o_lib->id;
            if ($i_status != 0 && $i_status != $o_album->status) {
                if (1 == $i_status && $o_album->cover != '') {
                    $o_album->status = $i_status;
                } else {
                    $o_album->status = $i_status;
                }
            }
            if ($s_bucket != $o_album->bucket) {
                $o_album->bucket = $s_bucket;
            }
            $o_album->save();
            //关联模特
            $o_models = LegModel::whereIn('id', $a_models)->where('status', '=', 1)->get();
            if (!$o_models || !count($o_models) || $o_models->count() <= 0) {
                throw new Exception("专辑关联模特失败！");
            }
            $a_model_rows = array();
            foreach ($o_models as $o_model) {
                $a_model_rows[] = array(
                    'album_id' => $o_album->id,
                    'model_id' => $o_model->id
                );
            }
            DB::table('relation_album_models')->where('album_id', '=', $o_album->id)->delete();
            DB::table('relation_album_models')->insert($a_model_rows);
            //关联标签
            $a_tags_rows = array();
            $o_tags = Tag::whereIn('id', $a_tags)->where('status', '=', 1)->get();
            if (!$o_tags || !count($o_tags) || $o_tags->count() <= 0) {
                throw new Exception("专辑关联标签失败！");
            }
            foreach ($o_tags as $o_tag) {
                $a_tags_rows[] = array(
                    'album_id' => $o_album->id,
                    'tag_id' => $o_tag->id
                );
            }
            DB::table('relation_album_tags')->where('album_id', '=', $o_album->id)->delete();
            DB::table('relation_album_tags')->insert($a_tags_rows);
            DB::commit();

            return $this->ajaxSuccessJson('专辑编辑成功！', 1, 'albums');
        } catch (\Exception $e) {
            DB::rollback();
            Log::info('album add error:' . $e->gerMessage());

            return $this->ajaxErrorJson('专辑编辑增失败！');
        }
    }
}
