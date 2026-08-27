<?php

namespace Flitt\Api\Checkout;

use Flitt\Api\Api;

/**
 * Bank-app deeplink creation for Open Banking / Installments checkout.
 * Reuses the same '/checkout/url/' endpoint as the regular hosted-checkout
 * Redirect method (Api\Checkout\Url) - a deeplink (instead of a hosted checkout
 * URL) is returned by that endpoint when 'payment_systems' is 'opb' or
 * 'installments' and 'payment_method' names a single supported bank.
 * @see https://docs.flitt.com/api/bank-app-deeplinks/
 */
class Deeplink extends Api
{
    private $url = '/checkout/url/';

    /**
     * Banks supported per payment_systems value, per docs.flitt.com/api/opb/ and
     * /api/installments/ (as of Aug 2026 - 'x' is the sandbox-only Demo Bank).
     * Update if Flitt adds banks to either flow.
     * @var array
     */
    private $allowedPaymentMethods = [
        'opb' => ['tbc', 'bog', 'credo', 'liberty', 'x'],
        'installments' => ['tbc', 'x']
    ];

    /**
     * Minimal required params to request a deeplink
     * @var array
     */
    private $requiredParams = [
        'merchant_id' => 'integer',
        'order_desc' => 'string',
        'amount' => 'integer',
        'currency' => 'string',
        'payment_systems' => 'string',
        'payment_method' => 'string'
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

    /**
     * @param $params
     * @return mixed
     */
    protected function prepareParams($params)
    {
        $prepared_params = parent::prepareParams($params);

        $paymentSystems = isset($prepared_params['payment_systems']) ? $prepared_params['payment_systems'] : null;
        if (!isset($this->allowedPaymentMethods[$paymentSystems])) {
            throw new \InvalidArgumentException(sprintf(
                'Deeplink creation requires \'payment_systems\' to be one of: %s',
                implode(', ', array_keys($this->allowedPaymentMethods))
            ));
        }

        $paymentMethod = isset($prepared_params['payment_method']) ? $prepared_params['payment_method'] : null;
        if (!in_array($paymentMethod, $this->allowedPaymentMethods[$paymentSystems], true)) {
            throw new \InvalidArgumentException(sprintf(
                'Deeplink creation requires a single \'payment_method\' for \'%s\', one of: %s',
                $paymentSystems,
                implode(', ', $this->allowedPaymentMethods[$paymentSystems])
            ));
        }

        return $prepared_params;
    }

}
