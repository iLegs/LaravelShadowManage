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
use App\Http\Controllers\ShadowController;

class ResourceUploadController extends ShadowController
{
    private static $types = array(
        1 => array(
            'prefix' => 'avatar/',//头像
        ),
        2 => array(
            'prefix' => 'cover/',//封面
        )
    );

    public function onPost()
    {
        $i_type = Input::get('resource_type', -1);
        if (-1 == $i_type || !in_array($i_type, array_keys(static::$types))) {
            return $this->errorJson('上传资源类型错误！');
        }
        // 构建鉴权对象
        $o_auth = new Auth(env('QINIU_AK'), env('QINIU_SK'));
        // 生成上传 Token
        $s_bucket = env('QINIU_SPACE');
        $s_token = $o_auth->uploadToken($s_bucket);
        $s_key = static::$types[$i_type]['prefix'] . date('Ymd', time()) . '/' . uniqid();
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
            $a_result = array(
                'statusCode' => 200,
                'message' => '上传成功！',
                'navTabId'=> '',
                'rel' => '',
                'callbackType' => 'closeCurrent',
                'forwardUrl' => ''
            );

            return Response::json($a_result, 200);
        }

        return $this->ajaxErrorJson('资源上传失败！');
    }
}
