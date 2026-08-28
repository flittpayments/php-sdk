<?php
require_once '../configuration.php';
require_once SDK_ROOTPATH . '/../vendor/autoload.php';

// Get company reports https://docs.flitt.com/api/reports/
//
// IMPORTANT: reports authenticate with a merchant-specific application_id and
// application private key - NOT the merchant_id/secret_key used everywhere
// else in this SDK. The values below are the shared Reports sandbox
// (application_id '1019', key 'test', merchant_id 1549902 - it has sample
// report data and isn't used for transactions; see tests/CompanyReportsTest.php),
// so this example runs as-is. For your own reports, contact Flitt support to
// obtain your own application_id/key and replace these with those.
\Flitt\Configuration::setReportsApplicationId('1019');
\Flitt\Configuration::setReportsApplicationKey('test');

try {
    $data = [
        'report_id' => 745, // "All transactions report", see the report list at https://docs.flitt.com/api/reports/
        'merchant_id' => 1549902,
        'on_page' => 10,
        'page' => 1,
        'filters' => [
            ['s' => 'order_timestart_from', 'm' => 'dateis', 'v' => date('Y-m-d', strtotime('-1 day'))],
            ['s' => 'order_timestart_to', 'm' => 'dateis', 'v' => date('Y-m-d')],
        ]
    ];
    $reports = \Flitt\CompanyReports::get($data);
    //getting returned data
    ?>
    <!doctype html>
    <html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <title>Company reports</title>
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
            <th style="text-align: center" colspan="2">Company reports request</th>
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
