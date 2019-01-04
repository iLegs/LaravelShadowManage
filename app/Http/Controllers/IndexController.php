<?php
/**
 * 前台首页控制器。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Controllers;

use Qiniu\Auth;
use App\Http\Models\Common\Banner;

class IndexController extends WebController
{
    public function onGet()
    {
        $a_result = $a_banners = array();
        $o_banners = Banner::where('status', '=', 1)
            ->orderBy('weight', 'ASC')
            ->get();
        if ($o_banners->count()) {
            $o_auth = new Auth(getenv('QINIU_AK'), getenv('QINIU_SK'));
            foreach ($o_banners as $banner) {
                $s_url = 'http://' . getenv('QINIU_DOMAIN') . '/' . $banner->qn_key;
                $a_banners[] = array(
                    'url' => $banner->url,
                    'desc' => $banner->title,
                    'pic' => $o_auth->privateDownloadUrl($s_url . '-banner', static::LIFE_TIME * 3)
                );
            }
        }
        $a_result['libs'] = $this->getLibs(1);
        $a_result['banners'] = $a_banners;

        return $this->returnView('index', $a_result);
    }
}
