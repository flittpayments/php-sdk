<?php
//Emulating payment result api 1.0 format json
//Run this via the built-in PHP dev server documented in the README
//(php -S localhost:8000), then request this script from a browser/curl.
//The target below is intentionally hardcoded rather than derived from
//$_SERVER['HTTP_HOST']/SERVER_PORT: those reflect the request's Host header,
//which a client controls, and using them to build a server-side cURL target
//is an SSRF pattern - it would let a request to this script make the server
//issue a request to an attacker-chosen host instead of its own dev server.
$response_url = "http://localhost:8000";
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => $response_url . "/examples/result.php",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "{\"rrn\": \"\", \"masked_card\": \"444455XXXXXX6666\", \"sender_cell_phone\": \"\", \"response_signature_string\": \"**********|111|GEL|111|123456|444455|VISA|GEL|7|444455XXXXXX6666|1549901|1549901_1da4a65b81fdaf934c535e352a776744|approved|21.05.2018 13:50:55|93088761|card|success|0|0|purchase\", \"response_status\": \"success\", \"sender_account\": \"\", \"fee\": \"\", \"rectoken_lifetime\": \"\", \"reversal_amount\": \"0\", \"settlement_amount\": \"0\", \"actual_amount\": \"111\", \"order_status\": \"approved\", \"response_description\": \"\", \"verification_status\": \"\", \"order_time\": \"21.05.2018 13:50:55\", \"actual_currency\": \"GEL\", \"order_id\": \"1549901_1da4a65b81fdaf934c535e352a776744\", \"parent_order_id\": \"\", \"merchant_data\": \"\", \"tran_type\": \"purchase\", \"eci\": \"7\", \"settlement_date\": \"\", \"payment_system\": \"card\", \"rectoken\": \"\", \"approval_code\": \"123456\", \"merchant_id\": 1549901, \"settlement_currency\": \"\", \"payment_id\": 93088761, \"product_id\": \"\", \"currency\": \"GEL\", \"card_bin\": 444455, \"response_code\": \"\", \"card_type\": \"VISA\", \"amount\": \"111\", \"sender_email\": \"\", \"signature\": \"7725ca95944de78550c3ca132c1e6602707afa90\"}",
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: application/json"
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    print ($response);
}