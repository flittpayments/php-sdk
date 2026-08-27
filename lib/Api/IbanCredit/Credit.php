<?php

namespace Flitt\Api\IbanCredit;

use Flitt\Api\Api;

/**
 * IBAN withdrawal (payout to a bank account by IBAN)
 * @see https://docs.flitt.com/api/create-order-ibancredit/
 */
class Credit extends Api
{
    private $url = '/ibancredit/';
    /**
     * Minimal required params to request an IBAN withdrawal
     * Note: as of this writing the API only supports 'GEL' as currency for IBAN
     * withdrawal (see docs); this is intentionally not enforced client-side so the
     * SDK doesn't go stale if that changes - the API returns error codes 1076/1077
     * for an invalid/unsupported IBAN or currency.
     * @var array
     */
    private $requiredParams = [
        'merchant_id' => 'integer',
        'order_desc' => 'string',
        'amount' => 'integer',
        'currency' => 'string',
        'receiver_iban' => 'iban'
    ];

    /**
     * @param $data
     * @param array $headers
     * @param array $requiredParams
     * @return mixed
     * @throws \Flitt\Exception\ApiException
     */
    public function get($data, $headers = [], $requiredParams = [])
    {
        if ($requiredParams)
            $this->requiredParams = array_merge($requiredParams, $this->requiredParams);
        $requestData = $this->prepareParams($data);
        $this->validate($requestData, $this->requiredParams);
        return $this->Request($method = 'POST', $this->url, $headers, $requestData);
    }

}
