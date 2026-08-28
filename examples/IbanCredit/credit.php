<?php
require_once '../configuration.php';
require_once SDK_ROOTPATH . '/../vendor/autoload.php';

// IBAN withdrawal (payout to a bank account by IBAN)
// see more https://docs.flitt.com/api/create-order-ibancredit/

try {
    \Flitt\Configuration::setMerchantId(1549901);
    \Flitt\Configuration::setCreditKey('testcredit'); // to generate you need to use the credit key
    // Test IBAN from https://docs.flitt.com/api/testing/ (status: processing). Of the
    // three published there, only this one satisfies the real ISO 7064 MOD-97 checksum
    // validated client-side by the SDK - GE00TB...0001 (approved) and GE00TB...0002
    // (declined) do not, so they can't be used here.
    $TestOrderData = [
        'currency' => 'GEL',
        'amount' => 1000,
        'receiver_iban' => 'GE00TB0000000000000003',
        'receiver_name' => 'Test Receiver'
    ];
    //Call method to generate the withdrawal order
    $orderData = Flitt\IbanCredit::credit($TestOrderData);
    //getting returned data
    ?>
    <!doctype html>
    <html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <title>IBAN withdrawal</title>
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
            <th style="text-align: center" colspan="2">IBAN withdrawal</th>
        </tr>
        <tr>
            <th style="text-align: left"
                colspan="2"><?php printf("<pre>%s</pre>", json_encode(['request' => $TestOrderData], JSON_PRETTY_PRINT)) ?></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Response status:</td>
            <td><?= $orderData->getData()['response_status'] ?></td>
        </tr>
        <tr>
            <td>Normal response:</td>
            <td>
                <pre><?php print_r($orderData->getData()); ?></pre>
            </td>
        </tr>
        <tr>
            <td>Check order data is valid:</td>
            <td><?php var_dump($orderData->isValid()); ?></td>
        </tr>
        </tbody>
    </table>
    </body>
    </html>
    <?php
} catch (\Exception $e) {
    echo "Fail: " . $e->getMessage();
}
