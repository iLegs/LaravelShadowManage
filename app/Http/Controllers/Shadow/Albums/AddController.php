<?php
/**
 * 新增专辑控制器。
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

class AddController extends ShadowController
{
    public function onGet()
    {
        $o_tags = Tag::where('status', '=', 1)->get();
        $o_legmodels = LegModel::where('status', '=', 1)->get();
        $o_libs = LegLib::where('status', '=', 1)->get();
        $a_result = array(
            'tags' => $o_tags,
            'legmodels' => $o_legmodels,
            'libs' => $o_libs,
            'next_day' => date('Y-m-d', time())
        );

        return $this->returnView('shadow.albums.add', $a_result);
    }

    public function onPost()
    {
        try {
            DB::beginTransaction();
            $s_title = Input::get('title', '');
            $s_desc = Input::get('desc', '');
            $a_tags = Input::get('tag', array());
            $a_models = Input::get('mdl', array());
            $i_lib = Input::get('lib', 0);
            $s_date = Input::get('date', '');
            $s_prefix = Input::get('prefix', '');
            $i_end = Input::get('end', 0);
            $s_postfix = Input::get('postfix', '');
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
            } elseif ('' == $s_prefix) {
                return $this->ajaxErrorJson('请输入前缀！');
            } elseif ($i_end <= 0) {
                return $this->ajaxErrorJson('请输入结束！');
            } elseif ('' == $s_postfix) {
                return $this->ajaxErrorJson('请输入后缀！');
            }
            $o_lib = LegLib::find($i_lib);
            if (!$o_lib || 1 != $o_lib->status) {
                return $this->ajaxErrorJson('图库数据异常！');
            }
            $o_album = new Album;
            $o_album->title = $s_title;
            if ('' != $s_desc) {
                $o_album->desc = $s_desc;
            }
            $o_album->date = $s_date;
            $o_album->year = date('Y', strtotime($s_date));
            $o_album->lib_id = $o_lib->id;
            $o_album->status = 0;
            $o_album->save();
            //添加图片
            $a_photo_rows = array();
            for ($i = 1; $i <= $i_end; $i++) {
                $s_num = $i . "";
                if ($i < 10) {
                    $s_num = sprintf('%02s', $i);
                }
                $s_qn_key = $s_prefix . $s_num . $s_postfix;
                $a_photo_rows[] = array(
                    'qn_key' => $s_qn_key,
                    'album_id' => $o_album->id,
                    'status' => 1
                );
            }
            DB::table('common_album_photoes')->insert($a_photo_rows);
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
            DB::table('relation_album_tags')->insert($a_tags_rows);
            DB::commit();

            return $this->ajaxSuccessJson('专辑新增成功！', 1, 'albums');
        } catch (\Exception $e) {
            DB::rollback();
            Log::info('album add error:' . $e->getMessage());

            return $this->ajaxErrorJson('专辑新增失败！');
        }
    }
}
