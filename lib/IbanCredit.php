<?php

namespace Flitt;

use Flitt\Api;
use Flitt\Response\Response;

/**
 * Class IbanCredit
 *
 * @package Flitt
 */
class IbanCredit
{
    /**
     * Request an IBAN withdrawal (payout)
     * @see https://docs.flitt.com/api/create-order-ibancredit/
     * @param $data
     * @param array $headers
     * @return Response
     * @throws Exception\ApiException
     */
    public static function credit($data, $headers = [])
    {
        $api = new Api\IbanCredit\Credit('credit');
        $result = $api->get($data, $headers);
        return new Response($result, 'credit');
    }
}
