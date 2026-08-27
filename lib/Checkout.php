<?php

namespace Flitt;

use Flitt\Api\Checkout as Api;
use Flitt\Response\Response;

/**
 * Class Checkout
 *
 * @package Flitt
 */
class Checkout
{
    /**
     * generate payment url
     * @param $data
     * @param array $headers
     * @return Response
     * @throws Exception\ApiException
     */
    public static function url($data, $headers = [])
    {
        $api = new Api\Url();
        $result = $api->get($data, $headers);
        return new Response($result);
    }

    /**
     * render payment form
     * @param $data
     * @return string
     * @throws Exception\ApiException
     */
    public static function form($data)
    {
        $api = new Api\Form();
        return $api->get($data);
    }

    /**
     * generate payment button string
     * @param $data
     * @return string
     * @throws Exception\ApiException
     */
    public static function button($data)
    {
        $api = new Api\Button();
        return $api->get($data);
    }

    /**
     * generate payment token
     * @param $data
     * @param array $headers
     * @return Response
     * @throws Exception\ApiException
     */
    public static function token($data, $headers = [])
    {
        $api = new Api\Token;
        $result = $api->get($data, $headers);
        return new Response($result);
    }

    /**
     * Get a bank-app deeplink for Open Banking / Installments checkout, instead of
     * a hosted checkout URL. Requires 'payment_systems' ('opb' or 'installments')
     * and a single-bank 'payment_method' in $data.
     * @see https://docs.flitt.com/api/bank-app-deeplinks/
     * @param $data
     * @param array $headers
     * @return Response
     * @throws Exception\ApiException
     */
    public static function deeplink($data, $headers = [])
    {
        $api = new Api\Deeplink();
        $result = $api->get($data, $headers);
        return new Response($result);
    }

}