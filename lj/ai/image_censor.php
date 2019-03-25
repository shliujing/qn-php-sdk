<?php
require_once __DIR__ . '/../../autoload.php';

use \Qiniu\Auth;
use Qiniu\Http\Client;

//  https://developer.qiniu.com/censor/api/5588/image-censor
$accessKey = getenv('QINIU_ACCESS_KEY');
$secretKey = getenv('QINIU_SECRET_KEY');

// 初始化Auth状态
$auth = new Auth($accessKey, $secretKey);

// 鉴权凭证
$url = "http://ai.qiniuapi.com/v1/image/censor";
$method = "POST";
$host = "ai.qiniuapi.com";
$body = "{ \"data\": { \"uri\": \"https://mars-assets.qnssl.com/resource/gogopher.jpg\" }, \"params\": { \"scenes\": [ \"pulp\", \"terror\", \"politician\", \"ads\" ] } }";
$contentType = "application/json";

$headers = $auth->authorizationV2($url, $method, $body, $contentType);
$headers['Content-Type'] = $contentType;
$headers['Host'] = $host;

$response = Client::post($url, $body, $headers);
if ($response->ok()) {
    $r = $response->json();
    var_dump($r);
}
