<?php
require_once __DIR__ . '/../autoload.php';

use Qiniu\Auth;
use Qiniu\Processing\PersistentFop;

// 去我们的portal 后台来获取AK, SK
$accessKey = getenv('QINIU_ACCESS_KEY');
$secretKey = getenv('QINIU_SECRET_KEY');
$bucket = getenv('QINIU_TEST_BUCKET');
$key = 'mkzip/mkzip-4-index.txt';

$auth = new Auth($accessKey, $secretKey);
// 异步任务的队列， 去后台新建： https://portal.qiniu.com/mps/pipeline
$pipeline = '12349';

$pfop = new PersistentFop($auth, null);

// 进行zip压缩的url
$url1 = 'http://test-pub.iamlj.com/2/1.png';
$url2 = 'http://test-pub.iamlj.com/miga0703/resources/images/259_close_12rollover1.png';

//压缩后的key
$zipKey = 'test/test1009.zip';

//$fops = 'mkzip/2/url/' . \Qiniu\base64_urlSafeEncode($url1);
//$fops .= '/url/' . \Qiniu\base64_urlSafeEncode($url2);


$fops = 'mkzip/4';
$fops .= '|saveas/' . \Qiniu\base64_urlSafeEncode("$bucket:$zipKey");

$notify_url = null;
$force = false;

list($id, $err) = $pfop->execute($bucket, $key, $fops, $pipeline, $notify_url, $force);

echo "\n====> pfop mkzip result: \n";
if ($err != null) {
    var_dump($err);
} else {
    echo "PersistentFop Id: $id\n";

    $res = "http://api.qiniu.com/status/get/prefop?id=$id";
    echo "Processing result: $res";
}
