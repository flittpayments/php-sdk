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
     * @deprecated use CompanyReports::get() instead. Reports moved to its
     * own top-level class since it authenticates with a separate
     * application_id/application key credential pair and lives on a
     * different host (portal.flitt.com) than the rest of this SDK. Kept
     * here as a backward-compatible alias - see CompanyReports and
     * https://docs.flitt.com/api/reports/.
     * @param $data
     * @return array
     * @throws Exception\ApiException
     */
    public static function reports($data)
    {
        return CompanyReports::get($data);
    }

}