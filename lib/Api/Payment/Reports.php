<?php

namespace Flitt\Api\Payment;

use Flitt\Configuration;
use Flitt\Helper\ApiHelper;
use Flitt\Helper\ResponseHelper;
use Flitt\Helper\ValidationHelper;
use Flitt\Exception\ApiException;

/**
 * Payment reports (portal.flitt.com). Requires a merchant-specific
 * application_id/application key configured via
 * Configuration::setReportsApplicationId()/setReportsApplicationKey() -
 * contact Flitt support to obtain them. These aren't part of the shared
 * sandbox test credentials used elsewhere in this SDK, so this endpoint
 * can't be covered by this SDK's own automated tests; see
 * examples/Payment/reports.php.
 * @see https://docs.flitt.com/api/reports/
 */
class Reports
{
    private $url = '/api/extend/company/report/';
    /**
     * Minimal required params to get a report
     * @var array
     */
    private $requiredParams = [
        'merchant_id' => 'integer',
        'report_id' => 'integer',
        'on_page' => 'integer',
        'page' => 'integer'
    ];

    /**
     * @param $data must include 'filters' (array), plus the required params
     * above - see https://docs.flitt.com/api/reports/ for report_id values,
     * their mandatory filter fields, and the filter object shape ({s, m, v}).
     * @return mixed
     * @throws ApiException
     */
    public function get($data)
    {
        if (!isset($data['merchant_id'])) {
            $data['merchant_id'] = Configuration::getMerchantId();
        }
        if (!isset($data['filters'])) {
            $data['filters'] = [];
        }
        ValidationHelper::validateRequiredParams($data, $this->requiredParams);

        $token = new ReportsToken();
        $accessToken = $token->get()['token'];

        $headers = [
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Token ' . $accessToken
        ];
        $response = Configuration::getHttpClient()->request(
            'POST',
            Configuration::getReportsApiUrl() . $this->url,
            $headers,
            ApiHelper::toJSON($data)
        );
        if (!$response) {
            throw new ApiException('Unknown error.');
        }
        $result = ResponseHelper::jsonToArray($response);
        if (isset($result['error']) || isset($result['err_code'])) {
            $message = isset($result['error']) ? $result['error'] : 'Request is incorrect.';
            throw new ApiException($message, 200, ['response' => $result]);
        }
        return $result;
    }
}
