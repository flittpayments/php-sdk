<?php

namespace Flitt\Api\CompanyReports;

use Flitt\Configuration;
use Flitt\Helper\ApiHelper;
use Flitt\Helper\ResponseHelper;
use Flitt\Helper\ValidationHelper;
use Flitt\Exception\ApiException;

/**
 * Company Reports (portal.flitt.com). Requires a merchant-specific
 * application_id/application key configured via
 * Configuration::setReportsApplicationId()/setReportsApplicationKey() -
 * contact Flitt support to obtain your own for production use. This is a
 * separate credential pair from the merchant_id/secret_key used elsewhere
 * in this SDK; a shared sandbox reports application
 * (application_id '1019', key 'test', merchant 1549902) is used by
 * tests/CompanyReportsTest.php - see also examples/CompanyReports/report.php.
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
