<?php

namespace Flitt\Api\Order;

use Flitt\Api\Api;

/**
 * Fiscal receipt data for an order. Replaces the old /get_atol_logs/ endpoint,
 * which is no longer reachable (returns HTTP 404) and isn't documented on
 * docs.flitt.com anymore.
 * @see https://docs.flitt.com/api/fiscal_data/
 */
class FiscalData extends Api
{
    private $url = '/fiscal_data/';
    /**
     * Minimal required params
     * @var array
     */
    private $requiredParams = [
        'merchant_id' => 'integer',
        'order_id' => 'string'
    ];

    /**
     * @param $data
     * @param array $headers
     * @return mixed
     * @throws \Flitt\Exception\ApiException
     */
    public function get($data, $headers = [])
    {
        $requestData = $this->prepareParams($data);
        $this->validate($requestData, $this->requiredParams);
        return $this->Request($method = 'POST', $this->url, $headers, $requestData);
    }

    /**
     * @param $params
     * @return mixed
     */
    protected function prepareParams($params)
    {
        $prepared_params = $params;

        if (!isset($prepared_params['merchant_id'])) {
            $prepared_params['merchant_id'] = $this->mid;
        }
        return $prepared_params;
    }
}
