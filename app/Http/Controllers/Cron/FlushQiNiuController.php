<?php
/**
 * 删除图片定时脚本控制器。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Controllers\Cron;

use Log;
use Redis;
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use App\Http\Models\Common\Album;
use App\Http\Models\Common\AlbumPhoto;
use App\Http\Controllers\Cron\CronController;

class PhotoDelController extends CronController
{
    const NUM = 100;

    const COVER_PREFIX = '/cover';

    public function onGet()
    {
        //封面图删除
        $s_ak = getenv('QINIU_AK');
        $s_sk = getenv('QINIU_SK');
        $s_bucket = getenv('QINIU_SPACE');
        $o_auth = new Auth($s_ak, $s_sk);
        $o_config = new \Qiniu\Config();
        $o_manage = new \Qiniu\Storage\BucketManager($o_auth, $o_config);
        $o_photoes = AlbumPhoto::where('status', '=', 2)->offset(0)->limit(static::NUM)->get();
        if ($o_photoes && $o_photoes->count()) {
            Log::info('start photo delete~');
            foreach ($o_photoes as $photo) {
                $err = $o_manage->delete($s_bucket, $photo->qn_key);
                if ($err) {
                    Log::error('cron photo delete error:' . $err);
                    continue;
                }
                $photo->status = 2;
                $photo->save();
                Log::info('delete success~');
            }
        }
        Log::info('start cover delete~');
        $o_albums = Album::all();
        $a_covers = array();
        foreach ($o_albums as $album) {
            $a_covers[] = $album->cover;
        }
        $s_ak = getenv('QINIU_AK');
        $s_sk = getenv('QINIU_SK');
        $s_bucket = getenv('QINIU_SPACE');
        $o_auth = new Auth($s_ak, $s_sk);
        $bucketManager = new BucketManager($o_auth);
        list($ret, $err) = $bucketManager->listFiles($s_bucket, static::COVER_PREFIX, '', 1000, null);
        if (null === $err) {
            foreach ($ret['items'] as $vv) {
                if (!in_array($vv['key'], $a_covers)) {
                    $err = $o_manage->delete($s_bucket, $vv['key']);
                    if ($err) {
                        Log::error('cron cover delete error:' . $err);
                        continue;
                    }
                }
            }
        }
        Log::info('done~');
    }
}
