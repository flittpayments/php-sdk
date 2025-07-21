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
