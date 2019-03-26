<?php
require_once __DIR__ . '/../../autoload.php';

use \Qiniu\Auth;
use Qiniu\Http\Client;

//  https://developer.qiniu.com/censor/api/5620/video-censor
// 发起视频审核
$accessKey = getenv('QINIU_ACCESS_KEY');
$secretKey = getenv('QINIU_SECRET_KEY');

// 初始化Auth状态
$auth = new Auth($accessKey, $secretKey);

// 初始化请求参数
$url = "http://ai.qiniuapi.com/v3/video/censor";
$method = "POST";
$host = "ai.qiniuapi.com";
$body = "{ \"data\": { \"uri\": \"https://mars-assets.qnssl.com/scene.mp4\" }, \"params\": { \"scenes\": [ \"pulp\", \"terror\", \"politician\" ], \"cut_param\": { \"interval_msecs\": 5000 } } }";
$contentType = "application/json";

// 鉴权凭证
$headers = $auth->authorizationV2($url, $method, $body, $contentType);
$headers['Content-Type'] = $contentType;
$headers['Host'] = $host;

$response = Client::post($url, $body, $headers);
if ($response->ok()) {
    $r = $response->json();
    var_dump($r);
}
