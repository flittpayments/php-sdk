<?php
/**
 * Author: DM
 */
error_reporting(-1);
ini_set('display_errors', 'On');

require 'vendor/autoload.php';
\Flitt\Configuration::setMerchantId(1549901);
\Flitt\Configuration::setSecretKey('test');
\Flitt\Configuration::setRequestType('json');//setting request type client
\Flitt\Configuration::setHttpClient('HttpCurl');//setting another client
\Flitt\Configuration::setApiUrl('pay.flitt.com'); //api base url
\Flitt\Configuration::setApiVersion('2.0'); //api base url

//start simple test
$dataC = [
    'order_id' => time(),
    'currency' => 'GEL',
    'amount' => 111,
    'response_url' => 'http://localhost/result.php'// response page
];

$data = \Flitt\Checkout::url($dataC);
$data->toCheckout();
//end
