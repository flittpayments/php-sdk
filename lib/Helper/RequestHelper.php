<?php
namespace Flitt\Helper;


class RequestHelper
{
    /**
     * @var array
     */
    private static $type = [
        'json' => 'application/json',
        'form' => 'application/x-www-form-urlencoded'
    ];

    /**
     * @param $headers
     * @param $type
     * @return array headers
     */
    public static function parseHeaders($headers, $type)
    {
        if (is_array($headers)) {
            array_push($headers, 'Content-Type: ' . self::$type[$type]);
        } else {
            $headers[] = 'Content-Type: ' . self::$type[$type];
        }

        return $headers;
    }
}