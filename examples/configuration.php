<?php
error_reporting(-1);
ini_set('display_errors', 'On');

/**
 * Setting up testing configuration
 * All testing details you can find here https://docs.flitt.com/api/testing/
 */
define('SDK_ROOTPATH', __DIR__);
require_once SDK_ROOTPATH . '/../vendor/autoload.php';
\Flitt\Configuration::setMerchantId(1549901);
\Flitt\Configuration::setSecretKey('test');
\Flitt\Configuration::setApiVersion('1.0');
\Flitt\Configuration::setRequestType('json');
