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
use App\Http\Models\Common\AlbumPhoto;
use App\Http\Controllers\Cron\CronController;

class PhotoDelController extends CronController
{
    const RDS_KEY = 'photo_del_queue';

    const RDS_DEL_KEY = 'photoes:album_';

    const NUM = 100;

    public function onGet()
    {
        $s_ips = getenv('CRON_IP');
        $a_ips = explode(',', $s_ips);
        $s_ak = getenv('QINIU_AK');
        $s_sk = getenv('QINIU_SK');
        $s_bucket = getenv('QINIU_SPACE');
        $o_auth = new Auth($s_ak, $s_sk);
        $o_config = new \Qiniu\Config();
        $o_manage = new \Qiniu\Storage\BucketManager($o_auth, $o_config);
        for ($i = 0; $i < static::NUM; $i++) {
            $s_result = Redis::lpop(static::RDS_KEY);
            if (!$s_result) {
                Log::info('photo delete data null');
                break;
            }
            $a_row = json_decode($s_result, true);
            $o_photo = AlbumPhoto::find($a_row['pid']);
            if (!$o_photo || $o_photo->status != 1) {
                continue;
            } elseif (!in_array($a_row['ip'], $a_ips)) {
                Log::info('photo delete ip error:' . $a_row['ip']);
                continue;
            }
            $err = $o_manage->delete($s_bucket, $o_photo->qn_key);
            if ($err) {
                Log::error('cron photo delete error:' . $err);
                continue;
            }
            $o_photo->status = 0;
            $o_photo->save();
            //$this->cleanRedisData(static::RDS_DEL_KEY . $o_photo->album_id);
            Log::info('cron photo delete success:' . $o_photo->qn_key);
        }

        return 'success';
    }
}
