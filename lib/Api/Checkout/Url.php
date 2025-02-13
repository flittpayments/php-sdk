<?php

namespace Flitt\Api\Checkout;

use Flitt\Api\Api;

class Url extends Api
{
    private $url = '/checkout/url/';
    /**
     * Minimal required params to get checkout
     * @var array
     */
    private $requiredParams = [
        'merchant_id' => 'integer',
        'order_desc' => 'string',
        'amount' => 'integer',
        'currency' => 'string'
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
        if (!empty($requiredParams))
            $this->requiredParams = array_merge($requiredParams, $this->requiredParams);
        $requestData = $this->prepareParams($data);
        $this->validate($requestData, $this->requiredParams);
        return $this->Request($method = 'POST', $this->url, $headers, $requestData);
    }

}