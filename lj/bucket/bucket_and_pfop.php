<?php
require_once __DIR__ . '/../../autoload.php';

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

$accessKey = getenv('QINIU_ACCESS_KEY');
$secretKey = getenv('QINIU_SECRET_KEY');
$bucket = "test-pub";
$key = "11.mp4";
//上传后的文件名
$newKey = "test/mp4/shuiyin_22.mp4";
// 初始化Auth状态
$auth = new Auth($accessKey, $secretKey);
$fops = "avthumb/mp4/wmImage/aHR0cDovL3Rlc3QtMi5xaW5pdWRuLmNvbS9sb2dvLnBuZw==/wmText/d2Vsb3ZlcWluaXU=/wmFontColor/cmVk/wmFontSize/60/wmGravityText/North". "/|saveas/" .\Qiniu\base64_urlSafeEncode("test-pub:".$newKey);;
$pipeline = "12349";

$fop = new Operation('test-pub.iamlj.com', $auth);

list($ret, $err) = $fop->execute($key, $fops);
echo "\n====> putFile result: \n";
if ($err !== null) {
    var_dump($err);
} else {
    var_dump($ret);
}
