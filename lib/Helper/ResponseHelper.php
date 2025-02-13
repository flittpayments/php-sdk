<?php

namespace Flitt\Helper;


class ResponseHelper
{
    /**
     * @param string
     * @return array
     */
    public static function getBase64Data($data)
    {
        if (isset($data['response']['token']))
            return $data['response'];
        if (isset($data['response']['data'])) {
            $orderData = json_decode(base64_decode($data['response']['data']), TRUE)['order'];
        } else {
            $orderData = $data['response'];
        }
        return $orderData;
    }

    /**
     * @param string
     * @return array
     */
    public static function jsonToArray($data)
    {
        return json_decode($data, TRUE);
    }


    /**
     * @param string
     * @return array
     */
    public static function formToArray($data)
    {
        $response = [];
        parse_str($data, $response);
        return $response;
    }
}