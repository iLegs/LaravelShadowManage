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
    const LIFE_TIME = 43200;

    const PAGE_SIZE = 6;

    const RDS_MODELS_KEY = 'search_models_lists';

    const RDS_TAGS_KEY = 'search_tags_lists';

    /**
     * Redis key 专辑列表。
     */
    const RDS_KEY = 'search_albums_list';

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

    public function onSearch($year, $mdl, $tg)
    {
        $a_arr = $this->getSearchResult($year, $mdl, $tg);
        $a_result = array(
            'leg_models' => $this->getAllModels(),
            'leg_tags' => $this->getAllTags(),
            'years' => static::$a_years,
            'page' => static::SEO_INDEX_PAGE,
            'albums' => isset($a_arr['rows']) ? $a_arr['rows'] : array(),
            'year' => $year,
            'mdl' => $mdl,
            'tg' => $tg
        );

        return $this->returnView('index', $a_result);
    }

    public function onGet()
    {
        $a_arr = $this->getSearchResult();
        $a_result = array(
            'leg_models' => $this->getAllModels(),
            'leg_tags' => $this->getAllTags(),
            'years' => static::$a_years,
            'page' => static::SEO_INDEX_PAGE,
            'albums' => isset($a_arr['rows']) ? $a_arr['rows'] : array(),
            'year' => 0,
            'mdl' => 0,
            'tg' => 0
        );

        return $this->returnView('index', $a_result);
    }

    public function onPost()
    {
        $i_page = Input::get('page', 1);
        $s_year = Input::get('year', 0);
        $i_model = Input::get('model', 0);
        $i_tag = Input::get('tag', 0);
        $a_arr = $this->getSearchResult($s_year, $i_model, $i_tag, $i_page);
        $a_result = array(
            'total' => isset($a_arr['total_count']) ? $a_arr['total_count'] : 0,
            'page' => $i_page,
            'models' => $i_model,
            'tags' => $i_tag,
            'year' => $s_year,
            'current_count' => isset($a_arr['rows']) ? count($a_arr['rows']) : 0,
            'rows' => isset($a_arr['rows']) ? $a_arr['rows'] : 0
        );

        return $this->successJson($a_result);
    }

    private function getAlbums()
    {
        $a_albums = array();
        $s_result = $this->getRedisData(static::RDS_KEY);
        if (false !== $s_result) {
            $a_albums = json_decode($s_result, true);
        } else {
            $o_albums = Album::where('status', '=', 1)->orderBy('date', 'DESC')->get();
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
                        'cover' => $o_auth->privateDownloadUrl($s_url . '-mobile_cover', static::LIFE_TIME * 3)
                    );
                }
                $this->setRedisData(static::RDS_KEY, json_encode($a_albums), static::LIFE_TIME);
            }
        }

        return $a_albums;
    }

    private function getAllModels()
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
                            'name' => $o_model->name,
                            'album_count' => $this->getModelAlbumCount($o_model->id)
                        );
                    }
                    $a_count = array_column($a_models, 'album_count');
                    array_multisort($a_count, SORT_DESC, $a_models);
                    $this->setRedisData(static::RDS_MODELS_KEY, json_encode($a_models), static::LIFE_TIME * 2);
                }
            }
        }

        return $a_models;
    }

    private function getModelAlbumCount($model_id)
    {
        $i_count = 0;
        $a_albums = $this->getAlbums();
        if (!count($a_albums)) {
            return $i_count;
        }
        foreach ($a_albums as $album) {
            if (in_array($model_id, array_column($album['models'], 'id'))) {
                $i_count += 1;
            }
        }

        return $i_count;
    }

    private function getTagAlbumCount($tag_id)
    {
        $a_albums = $this->getAlbums();
        $i_count = 0;
        if (!count($a_albums)) {
            return $i_count;
        }
        foreach ($a_albums as $album) {
            if (in_array($tag_id, array_column($album['tags'], 'id'))) {
                $i_count += 1;
            }
        }

        return $i_count;
    }

    private function getAllTags()
    {
        $a_tags = $a_tmp_tags = array();
        $s_tag_result = $this->getRedisData(static::RDS_TAGS_KEY);
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
                            'title' => $o_tag->title,
                            'album_count' => $this->getTagAlbumCount($o_tag->id)
                        );
                    }
                    $a_count = array_column($a_tags, 'album_count');
                    array_multisort($a_count, SORT_DESC, $a_tags);
                    $this->setRedisData(static::RDS_TAGS_KEY, json_encode($a_tags), static::LIFE_TIME * 2);
                }
            }
        }

        return $a_tags;
    }

    private function getSearchResult($year = 0, $mdl = 0, $tg = 0, $page = 0)
    {
        $year = (int) $year;
        $mdl = (int) $mdl;
        $tg = (int) $tg;
        $a_albums = $this->getAlbums();
        if (0 != $year && in_array($year, static::$a_years)) {
            $a_albums = array_filter($a_albums, function($element) use ($year) {
                return $year == $element['year'] ? 1 : 0;
            });
        }
        if (0 != $mdl) {
            $a_albums = array_filter($a_albums, function($element) use ($mdl) {
                return in_array($mdl, array_column($element['models'], 'id'));
            });
        }
        if (0 != $tg) {
            $a_albums = array_filter($a_albums, function($element) use ($tg) {
                return in_array($tg, array_column($element['tags'], 'id'));
            });
        }
        $i_count = count($a_albums);
        $a_rows = array();
        if (0 === $page) {
            $a_rows = array_slice($a_albums, 0, static::PAGE_SIZE * static::SEO_INDEX_PAGE);
        } else {
            $a_rows = array_slice($a_albums, ($page - 1) * static::PAGE_SIZE, static::PAGE_SIZE);
        }
        $a_result = array(
            'total_count' => $i_count,
            'current_count' => count($a_rows),
            'rows' => $a_rows
        );

        return $a_result;
    }
}
