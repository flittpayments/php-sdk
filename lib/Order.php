<?php

namespace Flitt;

use Flitt\Api\Order as Api;
use Flitt\Response\OrderResponse;

class Order
{
    /**
     * Generate request to capture order
     * @param $data
     * @param array $headers
     * @return OrderResponse
     * @throws Exception\ApiException
     */
    public static function capture($data, $headers = [])
    {
        $api = new Api\Capture();
        $result = $api->get($data, $headers);
        return new OrderResponse($result);
    }

    /**
     * Generate request to reverse order
     * @param $data
     * @param array $headers
     * @return OrderResponse
     * @throws Exception\ApiException
     */
    public static function reverse($data, $headers = [])
    {
        $api = new Api\Reverse();
        $result = $api->get($data, $headers);
        return new OrderResponse($result);
    }

    /**
     * Generate request to get order info
     * @param $data
     * @param array $headers
     * @return OrderResponse
     * @throws Exception\ApiException
     */
    public static function status($data, $headers = [])
    {
        $api = new Api\Status();
        $result = $api->get($data, $headers);
        return new OrderResponse($result);
    }

    /**
     * Generate request to get fiscal receipt data for an order
     * @see https://docs.flitt.com/api/fiscal_data/
     * @param $data
     * @param array $headers
     * @return OrderResponse
     * @throws Exception\ApiException
     */
    public static function fiscalData($data, $headers = [])
    {
        $api = new Api\FiscalData();
        $result = $api->get($data, $headers);
        return new OrderResponse($result);
    }
    /**
     * Generate request to create settlement order
     * @param $data
     * @param array $headers
     * @return Response\Response
     * @throws Exception\ApiException
     */
    public static function settlement($data, $headers = [])
    {
        $api = new Api\Settlements();
        $result = $api->get($data, $headers);
        return new Response\Response($result);
    }

}
