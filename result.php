<?php
error_reporting(-1);
ini_set('display_errors', 'On');
require 'vendor/autoload.php';
\Flitt\Configuration::setMerchantId(1549901);
\Flitt\Configuration::setSecretKey('test');
\Flitt\Configuration::setApiVersion('1.0');
\Flitt\Configuration::setRequestType('form');

$result = new \Flitt\Result\Result([], '', '', true);
var_dump($result->getData());
var_dump($result->isValid());
var_dump($result->isApproved());
