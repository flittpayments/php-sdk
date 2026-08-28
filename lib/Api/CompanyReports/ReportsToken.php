<?php

namespace Flitt\Api\CompanyReports;

use Flitt\Configuration;
use Flitt\Helper\ApiHelper;
use Flitt\Helper\ResponseHelper;
use Flitt\Exception\ApiException;

/**
 * Obtains a short-lived (1 hour) access token for the Company Reports API on
 * portal.flitt.com. This is a separate credential/auth system from the rest
 * of the SDK: a merchant-specific application_id + application private key,
 * not the regular merchant_id/secret_key - contact Flitt support to obtain
 * them, then set via Configuration::setReportsApplicationId()/
 * setReportsApplicationKey().
 * @see https://docs.flitt.com/api/reports/
 */
class ReportsToken
{
    private $url = '/authorizer/token/application/get';

    /**
     * @return array decoded {token, expires_in, request_id} response
     * @throws ApiException
     */
    public function get()
    {
        $applicationId = Configuration::getReportsApplicationId();
        $applicationKey = Configuration::getReportsApplicationKey();
        if (empty($applicationId) || empty($applicationKey)) {
            throw new ApiException(
                'Reports application_id/application key are not configured. ' .
                'Contact Flitt support to obtain them, then set via ' .
                'Configuration::setReportsApplicationId()/setReportsApplicationKey().'
            );
        }
        $date = date('Y-m-d H:i:s');
        $data = [
            'application_id' => $applicationId,
            'date' => $date,
            'signature' => ApiHelper::generateReportsSignature($applicationKey, $applicationId, $date)
        ];
        $headers = ['Content-Type: application/json; charset=utf-8'];
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
        if (isset($result['error_code']) || isset($result['error_message'])) {
            $message = isset($result['error_message']) ? $result['error_message'] : 'Request is incorrect.';
            throw new ApiException($message, 200, ['response' => $result]);
        }
        return $result;
    }
}
