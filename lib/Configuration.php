<?php

namespace Flitt;

use Flitt\HttpClient\ClientInterface;

class Configuration
{
    /**
     * Single source of truth for the SDK version: exposed to consumers as
     * Configuration::VERSION, and used to build the User-Agent header in
     * HttpCurl/HttpGuzzle so it never drifts out of sync with an
     * independently hardcoded string. Composer's own version resolution
     * (git tags) is separate from this - bump both together on release.
     */
    const VERSION = '2.1.0';

    /**
     * @var int Merchant ID
     */
    private static $MerchantID;
    /**
     * @var string Secret key
     */
    private static $SecretKey;
    /**
     * @var string Credit Key
     */
    private static $CreditKey;
    /**
     * @var string Reports (portal.flitt.com) API endpoint host
     */
    private static $ReportsApiUrl = 'portal.flitt.com';
    /**
     * @var string Reports application_id - a merchant-specific credential
     * obtained from Flitt support, separate from merchant_id/secret_key
     */
    private static $ReportsApplicationId;
    /**
     * @var string Reports application private key - a merchant-specific
     * credential obtained from Flitt support, used to sign the reports
     * access-token request (see Helper\ApiHelper::generateReportsSignature())
     */
    private static $ReportsApplicationKey;
    /**
     * @var string Api version default 1.0
     */
    private static $ApiVersion = '1.0';
    /**
     * @var string Api endpoint url
     */
    private static $ApiUrl = 'pay.flitt.com';
    /**
     * @var string Api endpoint path
     */
    private static $ApiPath = '/api';
    /**
     * @var string request Client
     */
    private static $HttpClient = 'HttpCurl';
    /**
     * @var string
     */
    private static $RequestType = 'json';

    /**
     * Define the Merchant ID.
     *
     * @param int $MerchantID
     */
    public static function setMerchantId($MerchantID)
    {
        self::$MerchantID = $MerchantID;
    }

    /**
     * @return int
     */
    public static function getMerchantId()
    {
        return self::$MerchantID;
    }

    /**
     * Define the $SecretKey.
     *
     * @param string $SecretKey
     */
    public static function setSecretKey($SecretKey)
    {
        self::$SecretKey = $SecretKey;
    }

    /**
     * @return string
     */
    public static function getSecretKey()
    {
        return self::$SecretKey;
    }

    /**
     * Define the $CreditKey.
     * @param $CreditKey
     */
    public static function setCreditKey($CreditKey)
    {
        self::$CreditKey = $CreditKey;
    }

    /**
     * @return string
     */
    public static function getCreditKey()
    {
        return self::$CreditKey;
    }

    /**
     * @return string
     */
    public static function getReportsApiUrl()
    {
        return 'https://' . self::$ReportsApiUrl;
    }

    /**
     * @param $ReportsApiUrl
     */
    public static function setReportsApiUrl($ReportsApiUrl)
    {
        self::$ReportsApiUrl = $ReportsApiUrl;
    }

    /**
     * @param $ReportsApplicationId
     */
    public static function setReportsApplicationId($ReportsApplicationId)
    {
        self::$ReportsApplicationId = $ReportsApplicationId;
    }

    /**
     * @return string
     */
    public static function getReportsApplicationId()
    {
        return self::$ReportsApplicationId;
    }

    /**
     * @param $ReportsApplicationKey
     */
    public static function setReportsApplicationKey($ReportsApplicationKey)
    {
        self::$ReportsApplicationKey = $ReportsApplicationKey;
    }

    /**
     * @return string
     */
    public static function getReportsApplicationKey()
    {
        return self::$ReportsApplicationKey;
    }

    /**
     * @return string The API version used for requests. Default is v1.0
     */
    public static function getApiVersion()
    {
        return self::$ApiVersion;
    }

    /**
     * @param $ApiVersion
     * @return string
     * @set string ApiVersion The API version to use for requests.
     */
    public static function setApiVersion($ApiVersion)
    {
        $versions = ['1.0', '2.0'];
        if (!in_array($ApiVersion, $versions)) {
            trigger_error('Undefined version! Available versions: \'1.0\', \'2.0\'', E_USER_NOTICE);
            return self::$ApiVersion = '1.0';
        }
        return self::$ApiVersion = $ApiVersion;
    }

    /**
     * @return string ApiUrl The API url to use for requests. Default is pay.flitt.com
     */
    public static function getApiUrl()
    {
        return 'https://' . self::$ApiUrl . self::$ApiPath;
    }

    /**
     * @param $ApiUrl
     * @set string ApiUrl The API url to use for requests.
     */
    public static function setApiUrl($ApiUrl)
    {
        self::$ApiUrl = $ApiUrl;
    }

    /**
     * @return string
     */
    public static function getHttpClient()
    {
        return self::setHttpClient(self::$HttpClient);
    }

    /**
     * @param $client
     * @return string
     */
    public static function setHttpClient($client)
    {
        if (is_string($client)) {
            $HttpClient = 'Flitt\\HttpClient\\' . $client;
            if (class_exists($HttpClient)) {
                return self::$HttpClient =  new $HttpClient();
            }
        } elseif ($client instanceof ClientInterface) {
            return self::$HttpClient = $client;
        }
        trigger_error('Client Class not found or name set up incorrectly. Available clients: HttpCurl, HttpGuzzle', E_USER_NOTICE);
        $HttpClient = 'Flitt\\HttpClient\\HttpCurl';
        return self::$HttpClient = new $HttpClient();

    }

    /**
     * @param $RequestType
     * @return string
     */
    public static function setRequestType($RequestType)
    {
        $types = ['json', 'form'];
        if (!in_array($RequestType, $types)) {
            trigger_error('Undefined request type! Available types: json, form', E_USER_NOTICE);
            return self::$RequestType = 'json';
        }
        return self::$RequestType = $RequestType;
    }

    /**
     * @set string ApiUrl The API url to use for requests.
     */
    public static function getRequestType()
    {
        return self::$RequestType;
    }
}
