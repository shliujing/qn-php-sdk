<?php
require_once __DIR__ . '/../../autoload.php';

use Qiniu\Auth;

$accessKey = getenv('QINIU_ACCESS_KEY');
$secretKey = getenv('QINIU_SECRET_KEY');
$bucket = getenv('QINIU_TEST_BUCKET');

$auth = new Auth($accessKey, $secretKey);

//获取回调的body信息
//$callbackBody = file_get_contents('php://input');
$callbackBody = '{"key":"test/png/0129/2.png","hash":"FqigP1GCnlm34WGXpLODMrlMpeNo","fsize":1253913,"bucket":"test-pub","name":"null"}';

//回调的contentType
$contentType = 'application/json';

//回调的签名信息，可以验证该回调是否来自七牛
//$authorization = $_SERVER['HTTP_AUTHORIZATION'];
$authorization = 'qbox 1omhuz5a7zjxssmjm1kwqkgupbckeuw9yxyy1ene:jkmnfnhrpbip-yozjx_z5uswqaw=';

//七牛回调的url，具体可以参考：http://developer.qiniu.com/docs/v6/api/reference/security/put-policy.html
$url = 'http://practice.dandantuan.com/demo/qiniu/qiniu_sdk_notify.php';

$isQiniuCallback = $auth->verifyCallback($contentType, $authorization, $url, $callbackBody);

if ($isQiniuCallback) {
    $resp = array('ret' => 'success');
} else {
    $resp = array('ret' => 'failed');
}

echo json_encode($resp);
