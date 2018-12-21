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
use App\Http\Models\Common\Photo;
use App\Http\Controllers\Cron\CronController;

class PhotoDelController extends CronController
{
    const RDS_KEY = 'photo_del_queue';

    /**
     * ip 白名单。
     */
    public static $a_ips = array(
    );

    public function onGet()
    {
        $s_result = Redis::lpop(static::RDS_KEY);
        if (!$s_result) {
            return 'null';
        }
        $a_row = json_decode($s_result, true);
        $o_photo = Photo::find($a_row['pid']);
        if (!$o_photo || $o_photo->status != 1) {
            return 'status error';
        } elseif (!in_array($a_row['ip'], static::$a_ips)) {
            return 'ip error';
        }
        $s_ak = getenv('QINIU_AK');
        $s_sk = getenv('QINIU_SK');
        $s_bucket = getenv('QINIU_SPACE');
        $o_auth = new Auth($s_ak, $s_sk);
        $o_config = new \Qiniu\Config();
        $o_manage = new \Qiniu\Storage\BucketManager($o_auth, $o_config);
        $err = $o_manage->delete($s_bucket, $o_photo->qn_key);
        if ($err) {
            Log::error('cron photo delete error:' . $err);

            return 'error';
        }
        $o_photo->status = 0;
        $o_photo->save();
        Log::info('cron photo delete success:' . $o_photo->qn_key);

        return 'success';
    }
}
