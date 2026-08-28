<?php

namespace Flitt\HttpClient;

use Flitt\Configuration;

class HttpGuzzle implements ClientInterface
{
    /**
     * Default options
     * @var array
     */
    private $curlOptions = [
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_HEADER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 60,
        CURLOPT_USERAGENT => 'php-sdk/' . Configuration::VERSION,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_SSL_VERIFYPEER => 1,
        CURLOPT_TIMEOUT => 60
    ];

    /**
     * @param string $method
     * @param string $url
     * @param array $headers
     * @param array $params
     * @return $this
     * @throws \Flitt\Exception\HttpClientException
     */
    public function request($method = 'post', $url = '', $headers = [], $params = [])
    {
        $method = strtolower($method);
        $this->isGuzzleHere();
        $client = new \GuzzleHttp\Client();
        $guzzleHeaders = [];
        foreach ($headers as $header) {
            $parts = explode(':', $header, 2);
            $name = trim($parts[0]);
            $value = isset($parts[1]) ? trim($parts[1]) : '';
            $guzzleHeaders[$name] = $value;
        }
        $data = [
            'body' => $params,
            'headers' => $guzzleHeaders,
            'curl' => $this->curlOptions,
        ];
        $request = $client->$method($url, $data);
        $response = $request->getBody()->getContents();
        return $response;
    }

    /**
     * @throws \Flitt\Exception\HttpClientException
     */
    private function isGuzzleHere()
    {
        if (!class_exists('\GuzzleHttp\Client'))
            throw new \Flitt\Exception\HttpClientException('Guzzle not found.');
    }
}