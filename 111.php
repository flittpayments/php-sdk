<?php
require 'vendor/autoload.php';
\Flitt\Configuration::setMerchantId(1549901);
\Flitt\Configuration::setSecretKey('test');

$checkoutData = [
    'currency' => 'GEL',
    'amount' => 1000
];
$data = \Flitt\Checkout::url($data);
$url = $data->getUrl();
$data->toCheckout();