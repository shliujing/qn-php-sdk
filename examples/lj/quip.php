<?php
require_once __DIR__ . '/../../autoload.php';
use Qiniu\Http\Client;

use Qiniu\Auth;

//对已经上传到七牛的视频发起异步转码操作
$accessKey = getenv('QINIU_ACCESS_KEY');
$secretKey = getenv('QINIU_SECRET_KEY');
$bucket = "test-pub";

$auth = new Auth($accessKey, $secretKey);

$host = "argus.atlab.ai";
$method = "POST";
$body = "{
    \"data\": {
        \"uri\": \"http://7xlv47.com1.z0.glb.clouddn.com/pulpsexy.jpg\"
    }
}";
$url = "http://argus.atlab.ai/v1/pulp";

$authHeaderArray = $auth->authorizationV2($url1, $method, $body, $contentType);
$authHeader = $authHeaderArray['Authorization'];
$contentType = "application/json";
$header = array(
    "Host" => $host ,
    "Authorization" => $authHeader,
    "Content-Type" => $contentType,
);

$response = Client::post($url, $body,$header);

echo "\n====> quip result: \n";
if ($err != null) {
    var_dump($response);
} else {
    echo "PersistentFop Id: $id\n";
}
