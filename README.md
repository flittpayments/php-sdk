# Flitt PHP-SDK

<p align="center">
  <img width="200" height="200" src="https://avatars.githubusercontent.com/u/193610400?s=400&u=67221561d3e401e91af724335984e878146dd0f9&v=4">
</p>

## Payment service provider
A payment service provider (PSP) offers shops online services for accepting electronic payments by a variety of payment methods including credit card, bank-based payments such as direct debit, bank transfer, and real-time bank transfer based on online banking. Typically, they use a software as a service model and form a single payment gateway for their clients (merchants) to multiple payment methods. 
[read more](https://en.wikipedia.org/wiki/Payment_service_provider)

## Installation

This SDK uses composer.

Composer is a tool for dependency management in PHP. It allows you to declare the libraries your project depends on and it will manage (install/update) them for you.

For more information on how to use/install composer, please visit https://github.com/composer/composer

#### Composer installation
```cmd
composer require flittpayments/php-sdk
```
#### Manual installation
```cmd
git clone -b master https://github.com/flittpayments/php-sdk.git
```

```php
<?php
require '/path-to-sdk/autoload.php';
```

> **Version 2.0** removed legacy XML content-type support - only `json` and `form`
> request types are supported now. See [CHANGELOG.md](CHANGELOG.md) for details.

## Simple Start
```php
require 'vendor/autoload.php';
\Flitt\Configuration::setMerchantId(1549901);
\Flitt\Configuration::setSecretKey('test');

$checkoutData = [
    'currency' => 'GEL',
    'amount' => 1000
];
$data = \Flitt\Checkout::url($checkoutData);
$url = $data->getUrl();
//$data->toCheckout() - redirect to checkout
```

## Get order status
```php
$orderData = \Flitt\Order::status(['order_id' => $order_id]);
$order = $orderData->getData();
echo $order['order_status']; // e.g. 'approved', 'declined', 'processing'
$orderData->isValid();       // verifies the response signature
```
See [examples/Order/status.php](examples/Order/status.php) for a full example, and
[docs.flitt.com/api/status](https://docs.flitt.com/api/status/) for the API reference.

## IBAN withdrawal
```php
\Flitt\Configuration::setCreditKey('testcredit'); // payout requests use the credit key, not the secret key
$data = \Flitt\IbanCredit::credit([
    'currency' => 'GEL',
    'amount' => 1000,
    'receiver_iban' => 'GE00TB0000000000000003'
]);
```
See [examples/IbanCredit/credit.php](examples/IbanCredit/credit.php) and
[docs.flitt.com/api/create-order-ibancredit](https://docs.flitt.com/api/create-order-ibancredit/).

## Open Banking / Installments deeplinks
```php
$data = \Flitt\Checkout::deeplink([
    'currency' => 'GEL',
    'amount' => 1000,
    'payment_systems' => 'opb',      // or 'installments'
    'payment_method' => 'tbc'        // a single bank; 'x' is the sandbox Demo Bank
]);
$deeplinkUrl = $data->getUrl();
```
See [examples/Checkout/deeplink.php](examples/Checkout/deeplink.php) and
[docs.flitt.com/api/bank-app-deeplinks](https://docs.flitt.com/api/bank-app-deeplinks/).

# Api

See [php-docs](https://flittpayments.github.io/php-docs/)
## Examples
To check it you can use build-in php server
```cmd
cd ~/php-sdk
php -S localhost:8000
```
[Checkout examples](https://github.com/flittpayments/php-sdk/tree/master/examples)

## Author

D.M.
