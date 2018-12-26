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
use App\Http\Models\Common\Tag;
use App\Http\Models\Common\Album;
use App\Http\Models\Common\LegModel;
use Illuminate\Support\Facades\DB;

class IndexController extends WebController
{
    const LIFE_TIME = 86400;

    const FLUSH_TIME = 3600;

    const PAGE_SIZE = 6;

    const RDS_MODELS_KEY = 'search_models';

    const RDS_TAGS_KEY = 'search_tags';

    /**
     * Redis key 专辑列表。
     */
    const RDS_KEY = 'albums_list';

    const SEO_INDEX_PAGE = 3;

    private static $a_years = array(
        '2019',
        '2018',
        '2017',
        '2016',
        '2015',
        '2014',
        '2013',
        '2012',
        '2011',
        '2010'
    );

    public function onGet()
    {
        $a_models = $a_tmp_models = array();
        $s_model_result = $this->getRedisData(static::RDS_MODELS_KEY);
        if (false !== $s_model_result) {
            $a_models = json_decode($s_model_result, true);
        } else {
            $o_r_models = DB::table('relation_album_models')->get();
            if ($o_r_models->count() > 0) {
                foreach ($o_r_models as $o_r_model) {
                    $a_tmp_models[] = $o_r_model->model_id;
                }
                $a_tmp_models = array_unique($a_tmp_models);
                $o_models = LegModel::whereIn('id', $a_tmp_models)
                    ->where('status', '=', 1)
                    ->get();
                if ($o_models->count() > 0) {
                    foreach ($o_models as $o_model) {
                        $a_models[] = array(
                            'id' => $o_model->id,
                            'name' => $o_model->name
                        );
                    }
                    $this->setRedisData(static::RDS_MODELS_KEY, json_encode($a_models), static::LIFE_TIME);
                }
            }
        }
        $s_tag_result = $this->getRedisData(static::RDS_TAGS_KEY);
        $a_tags = $a_tmp_tags = array();
        if (false !== $s_tag_result) {
            $a_tags = json_decode($s_tag_result, true);
        } else {
            $o_r_tags = DB::table('relation_album_tags')->get();
            if ($o_r_tags->count() > 0) {
                foreach ($o_r_tags as $o_r_tag) {
                    $a_tmp_tags[] = $o_r_tag->tag_id;
                }
                $a_tmp_tags = array_unique($a_tmp_tags);
                $o_tags = Tag::whereIn('id', $a_tmp_tags)
                    ->where('status', '=', 1)
                    ->get();
                if ($o_tags->count() > 0) {
                    foreach ($o_tags as $o_tag) {
                        $a_tags[] = array(
                            'id' => $o_tag->id,
                            'title' => $o_tag->title
                        );
                    }
                    $this->setRedisData(static::RDS_TAGS_KEY, json_encode($a_tags), static::LIFE_TIME);
                }
            }
        }
        $a_albums = $this->getAlbums();
        $a_result = array(
            'leg_models' => $a_models,
            'leg_tags' => $a_tags,
            'years' => static::$a_years,
            'page' => static::SEO_INDEX_PAGE,
            'albums' => array_slice($a_albums, 0, static::PAGE_SIZE * static::SEO_INDEX_PAGE)
        );

        return $this->returnView('index', $a_result);
    }

    public function onPost()
    {
        $i_page = Input::get('page', 1);
        $s_year = Input::get('year', 0);
        $i_model = Input::get('model', 0);
        $i_tag = Input::get('tag', 0);
        $a_albums = $this->getAlbums();
        if (0 != $s_year && in_array($s_year, static::$a_years)) {
            $a_albums = array_filter($a_albums, function($element) use ($s_year) {
                return $s_year == $element['year'] ? 1 : 0;
            });
        }
        if (0 != $i_model) {
            $a_albums = array_filter($a_albums, function($element) use ($i_model) {
                return in_array($i_model, array_column($element['models'], 'id'));
            });
        }
        if (0 != $i_tag) {
            $a_albums = array_filter($a_albums, function($element) use ($i_tag) {
                return in_array($i_tag, array_column($element['tags'], 'id'));
            });
        }
        $i_count = count($a_albums);
        $i_start = ($i_page - 1) * static::PAGE_SIZE;
        $a_rows = array_slice($a_albums, $i_start, static::PAGE_SIZE);
        $a_result = array(
            'total' => $i_count,
            'page' => $i_page,
            'models' => $i_model,
            'tags' => $i_tag,
            'year' => $s_year,
            'current_count' => count($a_rows),
            'rows' => $a_rows
        );

        return $this->successJson($a_result);
    }

    private function getAlbums()
    {
        $a_albums = array();
        $s_result = $this->getRedisData(static::RDS_KEY);
        if (false !== $s_result) {
            $a_albums = json_decode($s_result, true);
            $s_key = 'flush_albums';
            if (false == $this->getRedisData($s_key)) {
                $i_count = Album::where('status', '=', 1)->count();
                if ($i_count > count($a_albums)){
                    $a_ids = array_column($a_albums, 'id');
                    $o_albums = Album::where('status', '=', 1)->whereNotIn('id', $a_ids)
                        ->orderBy('year', 'DESC')
                        ->get();
                    $a_augmenters = array();
                    if ($o_albums && $o_albums->count() > 0) {
                        $o_auth = new Auth(getenv('QINIU_AK'), getenv('QINIU_SK'));
                        foreach ($o_albums as $o_album) {
                            $s_url = 'http://' . getenv('QINIU_DOMAIN') . '/' . $o_album->cover;
                            $a_augmenters[] = array(
                                'id' => $o_album->id,
                                'title' => $o_album->title,
                                'year' => $o_album->year,
                                'tags' => $o_album->getTags(),
                                'models' => $o_album->getModels(),
                                'cover' => $o_auth->privateDownloadUrl($s_url . '-mobile_cover', static::LIFE_TIME * 30)
                            );
                        }
                        $a_albums = array_merge($a_augmenters, $a_albums);
                        $this->setRedisData(static::RDS_KEY, json_encode($a_albums), (static::LIFE_TIME) * 2);
                        $this->setRedisData($s_key, 1, static::FLUSH_TIME);
                    }
                }
            }
        } else {
            $o_albums = Album::where('status', '=', 1)->orderBy('id', 'DESC')->get();
            $o_auth = new Auth(getenv('QINIU_AK'), getenv('QINIU_SK'));
            if ($o_albums && $o_albums->count() > 0) {
                foreach ($o_albums as $o_album) {
                    $s_url = 'http://' . getenv('QINIU_DOMAIN') . '/' . $o_album->cover;
                    $a_albums[] = array(
                        'id' => $o_album->id,
                        'title' => $o_album->title,
                        'year' => $o_album->year,
                        'tags' => $o_album->getTags(),
                        'models' => $o_album->getModels(),
                        'cover' => $o_auth->privateDownloadUrl($s_url . '-mobile_cover', static::LIFE_TIME * 30)
                    );
                }
                $this->setRedisData(static::RDS_KEY, json_encode($a_albums), (static::LIFE_TIME) * 2);
            }
        }

        return $a_albums;
    }
}
