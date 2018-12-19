<?php
/**
 * 上传资源控制器。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Controllers\Shadow;

use Input;
use Response;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use App\Http\Models\Common\Album;
use App\Http\Models\Common\QiNiuKey;
use App\Http\Controllers\ShadowController;

class ResourceUploadController extends ShadowController
{
    private static $types = array(
        0 => array(
            'prefix' => 'cover/',//封面
        ),
        1 => array(
            'prefix' => 'avatar/',//头像
        )
    );

    public function onPost()
    {
        $i_type = Input::get('resource_type', -1);
        $i_aid = Input::get('aid', 0);
        if (-1 == $i_type || !in_array($i_type, array_keys(static::$types))) {
            return $this->errorJson('上传资源类型错误！');
        }
        // 构建鉴权对象
        $o_auth = new Auth(env('QINIU_AK'), env('QINIU_SK'));
        // 生成上传 Token
        $s_bucket = env('QINIU_SPACE');
        $s_token = $o_auth->uploadToken($s_bucket);
        $s_key = static::$types[$i_type]['prefix'] . date('Ymd', time()) . '/' . uniqid() . '.jpg';
        if (!isset($_FILES["resource"]['tmp_name']) || $_FILES["resource"]['size'] <= 0) {
            return $this->errorJson('资源上传失败！');
        }
        $filePath = $_FILES["resource"]['tmp_name'];
        // 初始化 UploadManager 对象并进行文件的上传
        $uploadMgr = new UploadManager();
        // 调用 UploadManager 的 putFile 方法进行文件的上传
        $a_result = $uploadMgr->putFile($s_token, $s_key, $filePath);
        if (isset($a_result[0]['hash']) && isset($a_result[0]['key'])) {
            //todo key存入redis
            $o_key = new QiNiuKey;
            $o_key->qn_key = $a_result[0]['key'];
            $o_key->type = $i_type;
            $o_key->status = 0;
            $o_key->save();
            if ($i_aid != 0 && 0 == $i_type) {
                $o_album = Album::find($i_aid);
                if ($o_album) {
                    $o_album->cover = $o_key->qn_key;
                    if (0 == $o_album->status) {
                        $o_album->status = 1;
                    }
                    $o_album->save();
                }
                $o_key->status = 1;
                $o_key->save();

                return $this->ajaxSuccessJson('封面上传成功！', 1, 'albums');
            }

            return $this->ajaxSuccessJson('上传成功！');
        }

        return $this->ajaxErrorJson('资源上传失败！');
    }
}
