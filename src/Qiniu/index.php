<?php

use Qiniu\Auth;

$Secret_Key='KHvlG9e4sR2dwVaHuvOjVEejA7gskoBf0eJt2B7f';
$Access_Key='XT3rh2R5VH38uMem_FBxpCbeHevtBerm5cLz3WNn';
$url='http://we6.lionsoft.net.cn/qiniu/1.jpg';
$url='http://we6.lionsoft.net.cn/qiniu/2.jpg';

$Method='POST';
$Path='/v1/custom/carrecognition/processing/vehicle/analysis/picture';
$Host='argus.atlab.ai';
$contentType='application/json';

//$bodyStr='{"url":"'+$url+'"}';
$json = array(
    'url' => $url,
);
$bodyStr=json_encode($json);
#print bodyStr
$data = $Method . " " . $Path . "\nHost: " . $Host . "\nContent-Type: " . $contentType . "\n\n" . $bodyStr;
//print data
//$sign = hmac_sha1(Secret_Key,data)
$sign  = hash_hmac("sha1", $data, $Secret_Key,true);
//print map(ord,sign)
//encodedSign = base64.urlsafe_b64encode(sign)
$encodedSign = rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
//print encodedSign
$QiniuToken = "Qiniu " . $Access_Key . ":" . $encodedSign;

$host='http://'. $Host . $Path;

$url = $host;
$opt_data = $bodyStr;

$opt_data = json_encode($data);

$auth1 = new Auth($Secret_Key, $Access_Key);
$token1 = $auth1->sign($data);

//echo $encodedSign;
echo $token1;