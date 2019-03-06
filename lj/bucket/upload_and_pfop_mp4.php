<?php
require_once __DIR__ . '/../../autoload.php';

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

$accessKey = getenv('QINIU_ACCESS_KEY');
$secretKey = getenv('QINIU_SECRET_KEY');
$bucket = getenv('QINIU_TEST_BUCKET');
$pipeline = getenv('QINIU_TEST_PIPELINE');
$auth = new Auth($accessKey, $secretKey);

// 在七牛保存的文件名
$key = 'test/mp4/0226/11.mp4';

// 数据处理后的文件名
$newKey = 'test/mov/0226/11.mov';

// 要上传文件的本地路径
$filePath = '/Users/jingliu/Desktop/11.mp4';

$uploadMgr = new UploadManager();

$pfop = "avthumb/mov|saveas/" . \Qiniu\base64_urlSafeEncode($bucket . ":" . $newKey);

//转码完成后通知到你的业务服务器。（公网可以访问，并相应200 OK）
$notifyUrl = 'http://practice.dandantuan.com/demo/qiniu/qiniu_sdk_notify.php';
$callbackBody = "{\"filename\":\"$(fname)\",\"key\":\"$(key)\",\"hash\":\"$(etag)\",\"fsize\":$(fsize),\"bucket\":\"$(bucket)\",\"name\":\"$(x:name)\"}";

//独立的转码队列：https://portal.qiniu.com/mps/pipeline
$pipeline = getenv('QINIU_TEST_PIPELINE');

$policy = array(
    'callbackUrl' => $notifyUrl,
    'callbackBody' => $callbackBody,
    'persistentOps' => $pfop,
//    'deleteAfterDays' => 3,
    'persistentNotifyUrl' => $notifyUrl,
    'persistentPipeline' => $pipeline

);
$token = $auth->uploadToken($bucket, $key, 3600, $policy);

list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
echo "\n====> putFile result: \n";
if ($err !== null) {
    var_dump($err);
} else {
    var_dump($ret);
}
