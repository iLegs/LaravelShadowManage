<?php
/**
 * 图片信息表模型。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Models\Common;

use Log;
use Redis;
use Qiniu\Auth;
use Illuminate\Database\Eloquent\Model;

class AlbumPhoto extends Model
{
    /**
     * Redis 默认生命周期。
     */
    const LIFE_TIME = 86400;

    /**
     * redis 锁接口等待时间（秒）。
     */
    const LOCK_TIME = 2;

    /**
     * 接口休眠时间（微秒）。
     */
    const LOCK_WAIT_TIME = 10000;

    /**
     * 图片信息表。
     *
     * @var string
     */
    protected $table = 'common_album_photoes';

    protected $updated_at = false;

    protected $created_at = false;

    public $timestamps = false;

    protected static $a_buckets = array(
        'resource' => 'http://leg.imcn.vip/',
        'lolita' => 'http://ilolita.imcn.vip/'
    );

    public function album()
    {
        return $this->belongsTo('App\Http\Models\Common\Album', 'album_id', 'id');
    }

    public function getUrl()
    {
        $s_key = 'album_' . $this->album_id . '_photo_list:' . $this->id;
        $s_result = $this->getRedisData($s_key);
        $a_result = array();
        if (false !== $s_result) {
            $a_result = json_decode($s_result, true);
        } else {
            $auth = new Auth(getenv('QINIU_AK'), getenv('QINIU_SK'));
            $s_domain = static::$a_buckets['resource'];
            if ('resource' != $this->album->bucket) {
                $s_domain = static::$a_buckets['lolita'];
            }
            $baseUrl = $s_domain . $this->qn_key;
            $a_result = array(
                'preview' => $auth->privateDownloadUrl($baseUrl . '-preview', static::LIFE_TIME * 3),
                'original' => $auth->privateDownloadUrl($baseUrl, static::LIFE_TIME * 3)
            );
            $this->setRedisData($s_key, json_encode($a_result), static::LIFE_TIME);
        }

        return $a_result;
    }

    /**
     * 获取 redis 数据。
     * @param  string  $key     键值
     * @param  bool    $is_lock 是否加锁
     * @param  string  $token   锁机制值
     * @return mixed
     */
    protected function getRedisData($key, $is_lock = 0)
    {
        //键存在，直接返回
        if (0 == $is_lock && !Redis::exists($key)) {
            //键不存在，且不加锁。直接返回false，查库。
            return false;
        }
        $s_value = uniqid();
        //加锁
        do {
            $s_lock_key = 'lock:' . $key;
            if (Redis::exists($key)) {
                if (Redis::exists($s_lock_key) && Redis::get($s_lock_key) == $s_value) {
                    Redis::del($s_lock_key);
                }

                return Redis::get($key);
            }
            //当键不存在时设置值，返回 true 或 false
            $b_key_locked = Redis::set($s_lock_key, $s_value, 'ex', static::LOCK_TIME, 'nx'); //ex 秒
            if (!$b_key_locked) {
                // 1秒 = 1000000 微秒
                //睡眠，降低抢锁频率，缓解redis压力
                usleep(static::LOCK_WAIT_TIME);
                continue;
            }

            return false;
        } while (!$b_key_locked);
    }

    /**
     * 设置redis。
     * @param string    $key       键名
     * @param mixed     $value     值
     * @param int       $life_time 过期时间
     */
    protected function setRedisData($key, $value, $life_time = 86400)
    {
        $this->cleanRedisData($key);
        if (is_null($value) || '' == $value || !count($value)) {
            return false;
        }
        Redis::set($key, is_array($value) ? json_encode($value) : $value);
        if (static::LIFE_TIME >= 0) {
            Redis::expire($key, $life_time);
        }

        return true;
    }

    /**
     * 删除 redis 指定的 key。
     * @param  string $key 键名
     * @return bool
     */
    protected function cleanRedisData($key, $flag = 0)
    {
        try {
            //批量删除
            if (1 === $flag) {
                $a_keys = Redis::keys($key . '*');
                if (!count($a_keys)) {
                    return false;
                }
                Redis::del($a_keys);

                return true;
            }
            //普通删除
            if (Redis::exists($key)) {
                Redis::del($key);

                return true;
            }

            return false;
        } catch (Exception $e) {
            Log::info('redis del erroor:' . $key . 'msg:' . $e->getMessage());

            return false;
        }
    }
}
