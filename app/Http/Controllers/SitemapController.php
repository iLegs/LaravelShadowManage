<?php
/**
 * 前台公用控制器。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Controllers;

use App\Http\Models\Common\Tag;
use App\Http\Models\Common\Album;
use App\Http\Models\Common\LegLib;
use App\Http\Models\Common\LegModel;

class SitemapController extends WebController
{
    const LIFE_TIME = 86400;

    const RDS_SITEMAP_KEY = 'sitemap_xml_arr';

    public function onGet()
    {
        $s_result = $this->getRedisData(static::RDS_SITEMAP_KEY);
        $a_rds_result = array();
        if (false !== $s_result) {
            $a_rds_result = json_decode($s_result, true);
        }
        $a_result = array(
            'libs' => $this->getLibs($a_rds_result),
            'albums' => $this->getAlbums($a_rds_result),
            'tags' => $this->getTags($a_rds_result),
            'mdls' => $this->getModels($a_rds_result),
            'add_time' => isset($a_rds_result['add_time']) ? $a_rds_result['add_time'] : date('Y-m-d', time())
        );
        if (!isset($a_rds_result['libs']) || !isset($a_rds_result['albums']) || !isset($a_rds_result['tags']) || !isset($a_rds_result['mdls']) || !isset($a_rds_result['add_time'])) {
            $this->setRedisData(static::RDS_SITEMAP_KEY, json_encode($a_result), (static::LIFE_TIME * 2));
        }

        return response()->view('sitemap', $a_result)->header('Content-Type', 'text/xml');
    }

    private function getAlbums($arr)
    {
        if (isset($arr['albums']) && count($arr['albums'])) {
            return $arr['albums'];
        }
        $a_albums = array();
        $o_albums = Album::where('status', '=', 1)->orderBy('year', 'DESC')->get();
        if ($o_albums->count()) {
            foreach ($o_albums as $o_album) {
                $a_albums[] = $o_album->id;
            }
        }

        return $a_albums;
    }

    private function getLibs($arr)
    {
        if (isset($arr['libs']) && count($arr['libs'])) {
            return $arr['libs'];
        }
        $a_libs = array();
        $o_libs = LegLib::where('status', '=', 1)
            ->orderBy('id', 'ASC')
            ->get();
        if ($o_libs->count()) {
            foreach ($o_libs as $o_lib) {
                $a_libs[] = $o_lib->url;
            }
        }

        return $a_libs;
    }

    private function getTags($arr)
    {
        if (isset($arr['tags']) && count($arr['tags'])) {
            return $arr['tags'];
        }
        $a_tags = array();
        $o_tags = Tag::where('status', '=', 1)
            ->orderBy('id', 'ASC')
            ->get();
        if ($o_tags->count()) {
            foreach ($o_tags as $o_tag) {
                $a_tags[] = $o_tag->id;
            }
        }

        return $a_tags;
    }

    private function getModels($arr)
    {
        if (isset($arr['mdls']) && count($arr['mdls'])) {
            return $arr['mdls'];
        }
        $a_models = array();
        $o_models = LegModel::where('status', '=', 1)
            ->orderBy('id', 'ASC')
            ->get();
        if ($o_models->count()) {
            foreach ($o_models as $o_model) {
                $a_models[] = $o_model->id;
            }
        }

        return $a_models;
    }
}
