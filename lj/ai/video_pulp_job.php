<?php
require_once __DIR__ . '/../../autoload.php';

use \Qiniu\Auth;
use Qiniu\Http\Client;

//  https://developer.qiniu.com/censor/api/5620/video-censor
// 通过job_id获取视频审核结果
$accessKey = getenv('QINIU_ACCESS_KEY');
$secretKey = getenv('QINIU_SECRET_KEY');

// 初始化Auth状态
$auth = new Auth($accessKey, $secretKey);

// 初始化请求参数
$host = "ai.qiniuapi.com";
$method = "GET";
$jobId = "5c99f0da8a19ce0007975416";
$url = "http://ai.qiniuapi.com/v3/jobs/video/" . $jobId;
$contentType = "application/json";

// 鉴权凭证
$headers = $auth->authorizationV2($url, $method, "", $contentType);
$headers['Content-Type'] = $contentType;
$headers['Host'] = $host;

$response = Client::get($url, $headers);
if ($response->ok()) {
    $r = $response->json();
    var_dump($r);
}
