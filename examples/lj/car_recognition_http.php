<?php
require_once __DIR__ . '/../autoload.php';

use \Qiniu\Auth;

$accessKey = getenv('QINIU_ACCESS_KEY');
$secretKey = getenv('QINIU_SECRET_KEY');

// 初始化Auth状态
$auth = new Auth($accessKey, $secretKey);

// 鉴权凭证
$url1 = "http://argus.atlab.ai/v1/custom/carrecognition/processing/vehicle/analysis/picture";
$method = "POST";
$body = "{\"url\": \"http://we6.lionsoft.net.cn/qiniu/1.jpg\"}";
$contentType = "application/json";
$jqToken = $auth->authorizationV2($url1, $method, $body, $contentType);
print_r($jqToken);

$header = array(
    "Host" => "argus.atlab.ai",
    "Authorization" => "argus.atlab.ai",
    "Content-Type" => "argus.atlab.ai",
);
