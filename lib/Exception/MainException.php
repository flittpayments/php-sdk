<?php
namespace Flitt\Exception;

use Exception;

abstract class MainException extends Exception
{
    /**
     * @var int|null HTTP status code of the failed request, if known
     */
    private $httpStatus;
    /**
     * @var string|int|null PSP-specific error code from the API response ('error_code'), if any
     */
    private $pspErrorCode;
    /**
     * @var array|null Raw decoded response body, if any
     */
    private $json;
    /**
     * @var string|null API-supplied request_id, if any - include this when contacting Flitt support
     */
    private $requestId;

    public function __construct(
        $message,
        $httpStatus = null,
        $json = null
    )
    {
        $this->httpStatus = $httpStatus;
        $this->json = $json;

        $errorCode = isset($json['response']['error_code']) ? $json['response']['error_code'] : null;
        $errorMessage = isset($json['response']['error_message']) ? $json['response']['error_message'] : null;
        $requestId = isset($json['response']['request_id']) ? $json['response']['request_id'] : null;

        $this->pspErrorCode = $errorCode;
        $this->requestId = $requestId;

        if ($errorMessage !== null) {
            $message = rtrim($errorMessage, '.') . ".\n" . $message;
        }
        if ($requestId !== null) {
            $message .= ' Request ID: ' . $requestId;
        }

        parent::__construct($message);
    }

    /**
     * @return string|int|null The PSP-specific error code from the API response, if any.
     */
    public function getPspErrorCode()
    {
        return $this->pspErrorCode;
    }

    /**
     * @return int|null The HTTP status code of the failed request, if known.
     */
    public function getHttpStatus()
    {
        return $this->httpStatus;
    }

    /**
     * @return array|null The raw decoded response body, if any.
     */
    public function getJsonBody()
    {
        return $this->json;
    }

    /**
     * @return string|null The request_id from the API response, if any.
     */
    public function getRequestId()
    {
        return $this->requestId;
    }
}
