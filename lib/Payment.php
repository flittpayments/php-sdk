<?php

namespace Flitt;

use Flitt\Api\Payment as Api;
use Flitt\Response\Response;

class Payment
{
    /**
     * Generate request to recurring by rectoken
     * @param $data
     * @param array $headers
     * @return Response
     * @throws Exception\ApiException
     */
    public static function recurring($data, $headers = [])
    {
        $api = new Api\Rectoken();
        $result = $api->get($data, $headers);
        return new Response($result);
    }

    /**
     * Get payment reports (portal.flitt.com). Requires
     * Configuration::setReportsApplicationId()/setReportsApplicationKey() to
     * be set first - see Api\Payment\Reports and
     * https://docs.flitt.com/api/reports/. Returns the decoded report
     * response directly ({data, fields, rows_count, rows_page, rows_on_page})
     * rather than wrapped in Response, since this endpoint uses bearer-token
     * auth and has no merchant-secret-key signature to verify.
     * @param $data
     * @return array
     * @throws Exception\ApiException
     */
    public static function reports($data)
    {
        $api = new Api\Reports();
        return $api->get($data);
    }

}