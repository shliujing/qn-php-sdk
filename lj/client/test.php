<?php
require_once __DIR__ . '/../../../autoload.php';
use Qiniu\Http\Client;

$url = 'https://api.qiniu.com/v2/query?ak=Yp8bugf6wRMhl8Fv5LtiHUyzmzB5hYJxfVh1ztLW&bucket=tspsaas';
$body = '';
$headers = array();

//$response = Client::post($url, $body, $headers);
$response = Client::get($url, $headers);
if ($response->ok()) {
    $r = $response->json();
    var_dump($r);
}
