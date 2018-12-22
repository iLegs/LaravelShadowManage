<?php
/**
 * 七牛云图片搜索管理控制器。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

namespace App\Http\Controllers\Shadow\QiniuFile;

use Input;
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use App\Http\Controllers\ShadowController;

class ManageController extends ShadowController
{
    public function any()
    {
        $s_ak = getenv('QINIU_AK');
        $s_sk = getenv('QINIU_SK');
        $s_bucket = getenv('QINIU_SPACE');
        $o_auth = new Auth($s_ak, $s_sk);
        $bucketManager = new BucketManager($o_auth);
        $a_rows = array();
        // 要列取文件的公共前缀
        $s_prefix = Input::get('keywords', '');
        $s_marker = Input::get('marker', '');
        if ('' != $s_prefix) {
            // 上次列举返回的位置标记，作为本次列举的起点信息。
            // 本次列举的条目数
            $i_limit = 20000;
            $s_delimiter = '/';
            // 列举文件
            list($ret, $err) = $bucketManager->listFiles($s_bucket, $s_prefix, $s_marker, $i_limit, $s_delimiter);
            if (null === $err) {
                if (array_key_exists('marker', $ret)) {
                    $s_marker = $ret["marker"];
                }
                foreach ($ret['items'] as $vv) {
                    $s_str = $vv['key'];
                    $a_str = explode('_', $s_str);
                    $s_key = $a_str[0] . '_' . $a_str[1];
                    $a_rows[$s_key] = $s_str;
                }
            }
        }
        $a_reuslt = array(
            'keywords' => $s_prefix,
            'rows' => $a_rows,
            'marker' => $s_marker
        );

        return $this->returnView('shadow.qiniu_file.manage', $a_reuslt);
    }
}
