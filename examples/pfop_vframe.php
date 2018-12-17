<?php
require_once __DIR__ . '/../autoload.php';

/**
 * 参考文档 视频帧缩略图 :https://developer.qiniu.com/dora/manual/1313/video-frame-thumbnails-vframe
 * int http://lql.iamlj.com/test/test.mp4
 * out http://lql.iamlj.com/test_1.0.jpg
 * out http://lql.iamlj.com/test_1.1.jpg
 * ...
 */

use Qiniu\Auth;
use Qiniu\Processing\PersistentFop;

//对已经上传到七牛的视频发起异步转码操作
$accessKey = getenv('QINIU_ACCESS_KEY');
$secretKey = getenv('QINIU_SECRET_KEY');
$bucket = "28-test";
$domain = "http://lql.iamlj.com/";
$auth = new Auth($accessKey, $secretKey);

//要转码的文件所在的空间和文件名。
$key = 'test/test.mp4';

//转码是使用的队列名称。 https://portal.qiniu.com/mps/pipeline
$pipeline = 'pl-lql';

//转码完成后通知到你的业务服务器。
$notifyUrl = null;
$force = false;

$config = new \Qiniu\Config();
$config->useHTTPS = true;
$pfop = new PersistentFop($auth, $config);

$sec = 1;
for ($i = 0; $i < 10; $i++) {
//要进行视频截图操作
    $filename = "2018/08/29/test_" . $sec . "." . $i . ".jpg";
    $fops = "vframe/jpg/offset/" . $sec . "." . $i . "/w/540/h/960|saveas/" .
        \Qiniu\base64_urlSafeEncode($bucket . ":" . $filename);

    list($id, $err) = $pfop->execute($bucket, $key, $fops, $pipeline, $notifyUrl, $force);

    if ($err != null) {
        var_dump($err);
    } else {
        echo $domain . $filename;
        echo "\n";
    }

//查询转码的进度和状态
//    list($ret, $err) = $pfop->status($id);
//    echo "\n====> pfop avthumb status: \n";
//    if ($err != null) {
//        var_dump($err);
//    } else {
//        var_dump($ret);
//    }
}
