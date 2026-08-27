<?php
require_once '../configuration.php';
require_once SDK_ROOTPATH . '/../vendor/autoload.php';

// Bank-app deeplink creation for Open Banking / Installments checkout
// see more https://docs.flitt.com/api/bank-app-deeplinks/
//
// Important, per the docs above:
// - The customer must trigger the bank app link, not your code - only open it
//   from a visible, user-initiated control (e.g. a button click), never from an
//   auto-redirect, a timer, or a background action.
// - Do not construct/rewrite/decode/re-encode/shorten the returned URL, and do
//   not append parameters to it - use it exactly as returned.
// - Iframes are not supported for opening bank payment URLs.
// - On desktop, show a QR code for the URL instead of opening the bank app link.
// - Treat the browser/app return to your site as informational only - always
//   confirm the final order state via a callback or an order status request.

try {
    $OpenBankingOrderData = [
        'currency' => 'GEL',
        'amount' => 1000,
        'payment_systems' => 'opb',
        'payment_method' => 'x' // 'x' = sandbox Demo Bank; use 'tbc'|'bog'|'credo'|'liberty' in production
    ];
    $opbDeeplink = Flitt\Checkout::deeplink($OpenBankingOrderData);

    $InstallmentsOrderData = [
        'currency' => 'GEL',
        'amount' => 1000,
        'payment_systems' => 'installments',
        'payment_method' => 'x' // 'x' = sandbox Demo Bank; use 'tbc' in production (only bank supported today)
    ];
    $installmentsDeeplink = Flitt\Checkout::deeplink($InstallmentsOrderData);
    ?>
    <!doctype html>
    <html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <title>Open Banking / Installments deeplink</title>
        <style>
            table tr td, table tr th {
                padding: 10px;
            }
        </style>
    </head>
    <body>
    <table style="margin: auto" border="1">
        <thead>
        <tr>
            <th style="text-align: center" colspan="2">Open Banking deeplink</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Deeplink URL:</td>
            <td><?= $opbDeeplink->getUrl() ?></td>
        </tr>
        </tbody>
        <thead>
        <tr>
            <th style="text-align: center" colspan="2">Installments deeplink</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Deeplink URL:</td>
            <td><?= $installmentsDeeplink->getUrl() ?></td>
        </tr>
        </tbody>
    </table>
    </body>
    </html>
    <?php
} catch (\Exception $e) {
    echo "Fail: " . $e->getMessage();
}
