<?php
require_once '../configuration.php';
require_once SDK_ROOTPATH . '/../vendor/autoload.php';

// Get payment reports https://docs.flitt.com/api/reports/
//
// IMPORTANT: reports authenticate with a merchant-specific application_id and
// application private key - NOT the merchant_id/secret_key used everywhere
// else in this SDK. Contact Flitt support to obtain your own; the sandbox
// test credentials used in the other examples do NOT work here, so this
// example cannot be run as-is - replace the placeholders below with your own.
\Flitt\Configuration::setReportsApplicationId('%your_application_id%');
\Flitt\Configuration::setReportsApplicationKey('%your_application_private_key%');

try {
    $data = [
        'report_id' => 745, // "All transactions report", see the report list at https://docs.flitt.com/api/reports/
        'on_page' => 10,
        'page' => 1,
        'filters' => [
            ['s' => 'order_timestart_from', 'm' => 'dateis', 'v' => date('Y-m-d', strtotime('-1 day'))],
            ['s' => 'order_timestart_to', 'm' => 'dateis', 'v' => date('Y-m-d')],
        ]
    ];
    $reports = \Flitt\Payment::reports($data);
    //getting returned data
    ?>
    <!doctype html>
    <html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <title>Payment reports</title>
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
            <th style="text-align: center" colspan="2">Payment reports request</th>
        </tr>
        <tr>
            <th style="text-align: left"
                colspan="2"><?php printf("<pre>%s</pre>", json_encode(['request' => $data], JSON_PRETTY_PRINT)) ?></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Normal response:</td>
            <td style="max-width: 1000px">
                <pre style="word-wrap: break-word;    white-space: pre-wrap;"><?php print_r($reports); ?></pre>
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
