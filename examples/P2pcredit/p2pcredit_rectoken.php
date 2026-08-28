<?php
require_once '../configuration.php';
require_once SDK_ROOTPATH . '/../vendor/autoload.php';


//P2P card credit (payout) to a card previously used for a purchase, using the
//rectoken obtained from that purchase instead of a raw card number - see more
//https://docs.flitt.com/api/create-order-p2pcredit/
try {
    //Step 1: a purchase with required_rectoken=Y to obtain a rectoken
    \Flitt\Configuration::setMerchantId(1549901);
    \Flitt\Configuration::setSecretKey('test');
    $purchaseData = [
        'currency' => 'GEL',
        'amount' => 1000,
        'client_ip' => '127.2.2.1',
        'card_number' => '4444555511116666',
        'cvv2' => '333',
        'expiry_date' => '1222',
        'required_rectoken' => 'Y'
    ];
    $purchase = Flitt\Pcidss::start($purchaseData);
    $rectoken = $purchase->getData()['rectoken'];

    //Step 2: p2p credit (payout) using that rectoken, with the credit key
    \Flitt\Configuration::setCreditKey('testcredit'); // payout requests use the credit key, not the secret key
    $creditData = [
        'currency' => 'GEL',
        'amount' => 500,
        'receiver_rectoken' => $rectoken
    ];
    $orderData = Flitt\P2pcredit::start($creditData);
    //getting returned data
    ?>
    <!doctype html>
    <html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <title>P2P credit via rectoken</title>
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
            <th style="text-align: center" colspan="2">P2P credit via rectoken</th>
        </tr>
        <tr>
            <th style="text-align: left"
                colspan="2"><?php printf("<pre>%s</pre>", json_encode(['request' => $creditData], JSON_PRETTY_PRINT)) ?></th>
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
