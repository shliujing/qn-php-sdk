<?php
/**
 * Created by PhpStorm.
 * User: jingliu
 * Date: 2019/1/17
 * Time: 5:02 PM
 */

require_once __DIR__ . '/../qvm/lib/client.php';
require_once __DIR__ . '/../qvm/lib/interfaces/describe_instance_list.php';


//$publicKey = getenv('QINIU_TEST_PUBLIC_KEY');
//$secretKey = getenv('QINIU_TEST_SECRET_KEY');

$publicKey = '07bd4b15f0364ee48bd86295d01f575e';
$secretKey = 'oASRnxUA4gdw6SEcztRcNCK5qA37o5L6Vt8l';
$gatewayBaseUrl = 'https://qvm-openapi.qiniu.io';

try {
    $response = Client::getInstance($publicKey, $secretKey)
        ->setGatewayBaseURL($gatewayBaseUrl)
        ->Request(
            DescribeInstanceList::getInstance()
                ->setPage(1)
                ->setPageSize(30)
        );
} catch (Exception $e) {
    print_r($e);
    return;
}

print_r($response);