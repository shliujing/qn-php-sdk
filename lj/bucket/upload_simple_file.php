<?php
require_once __DIR__ . '/../../autoload.php';

// 引入鉴权类
use Qiniu\Auth;

// 引入上传类
use Qiniu\Storage\UploadManager;

// 需要填写你的 Access Key 和 Secret Key
$accessKey = getenv('QINIU_ACCESS_KEY');
$secretKey = getenv('QINIU_SECRET_KEY');
$bucket = "test-pub";

// 构建鉴权对象
$auth = new Auth($accessKey, $secretKey);

// 要上传文件的本地路径
$filePath = '/Users/jingliu/Desktop/test-desktop.png';

// 上传到七牛后保存的文件名
$key = 'test/png/0129/2.png';

//带回调业务服务器的凭证（application/json）
$policy = array(
    'callbackUrl' => 'http://practice.dandantuan.com/demo/qiniu/qiniu_sdk_notify.php',
    'callbackBody' => '{"key":"$(key)","hash":"$(etag)","fsize":$(fsize),"bucket":"$(bucket)","name":"$(x:name)"}',
    'callbackBodyType' => 'application/json'
);

// 生成上传 Token
$token = $auth->uploadToken($bucket, $key, 3600, $policy);

// 初始化 UploadManager 对象并进行文件的上传。
$uploadMgr = new UploadManager();

// 调用 UploadManager 的 putFile 方法进行文件的上传。
list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
echo "\n====> putFile result: \n";

if ($err !== null) {
    var_dump($err);
} else {
    var_dump($ret);
}