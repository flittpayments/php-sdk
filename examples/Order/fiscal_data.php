<?php
require_once '../configuration.php';
require_once SDK_ROOTPATH . '/../vendor/autoload.php';


//Fiscal receipt data - see more https://docs.flitt.com/api/fiscal_data/
//Note: fiscalisation is only available for merchants in Uzbekistan.
try {
    $TestOrderData = [
        'order_id' => time(),
        'card_number' => '4444555511116666',
        'cvv2' => '333',
        'expiry_date' => '1232',
        'currency' => 'GEL',
        'amount' => 1000,
        'client_ip' => '127.2.2.1'
    ];
    //Call method to generate order
    $order_data = Flitt\Pcidss::start($TestOrderData);
    $fiscal_data = Flitt\Order::fiscalData(['order_id' => $TestOrderData['order_id']]);
    //getting returned data
    ?>
    <!doctype html>
    <html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <title>Order fiscal data</title>
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
            <th style="text-align: center" colspan="2">Fiscal data request</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Response status:</td>
            <td><?= $fiscal_data->getData()['response_status'] ?></td>
        </tr>
        <tr>
            <td>Normal response:</td>
            <td>
                <pre><?php print_r($fiscal_data->getData()); ?></pre>
            </td>
        </tr>
        </tbody>
    </table>
    </body>
    </html>
    <?php
} catch (\Exception $e) {
    echo "Fail: " . $e->getMessage();
}
