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
     * Generate request to get payments reports
     * @param $data
     * @param array $headers
     * @return Response
     * @throws Exception\ApiException
     */
    public static function reports($data, $headers = [])
    {
        $api = new Api\Reports();
        $result = $api->get($data, $headers);
        return new Response($result);
    }

}