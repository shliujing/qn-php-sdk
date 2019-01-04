<?php
require_once __DIR__ . '/../../../autoload.php';

use \Qiniu\Auth;
use Qiniu\Http\Client;

$accessKey = getenv('QINIU_ACCESS_KEY');
$secretKey = getenv('QINIU_SECRET_KEY');

// 初始化Auth状态
$auth = new Auth($accessKey, $secretKey);

// 鉴权凭证
$url = "http://ai.qiniuapi.com/v1/ocr/idcard";
$method = "POST";
$host = "ai.qiniuapi.com";
$body = "{ \"data\": { \"uri\": \"http://test-pub.iamlj.com/test-idcard.jpg\" } }";
$contentType = "application/json";

$headers = $auth->authorizationV2($url, $method, $body, $contentType);
$headers['Content-Type'] = $contentType;
$headers['Host'] = $host;

$response = Client::post($url, $body, $headers);
if ($response->ok()) {
    $r = $response->json();
    var_dump($r);
}
