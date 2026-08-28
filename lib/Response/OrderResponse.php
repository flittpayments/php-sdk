<?php

namespace Flitt\Response;

use Flitt\Helper\ResponseHelper;

class OrderResponse extends Response
{
    /**
     * @return array|mixed
     */
    public function getData()
    {
        if ($this->apiVersion === '2.0') {
            if (isset($this->response['response']['data'])) {
                return ResponseHelper::getBase64Data($this->response);
            } else {
                return $this->response['response'];
            }
        } else {
            return $this->response['response'];
        }
    }

    /**
     * @return bool
     */
    public function isReversed()
    {
        $data = $this->buildVerifyData();
        if (!isset($data['reverse_status']))
            return false;
        $valid = $this->isValid($data);
        if ($valid && $data['reverse_status'] === 'approved')
            return true;

        return false;
    }

    /**
     * @param bool $direct if synchronous call
     * @return bool
     */
    public function isCaptured($direct = false)
    {
        $data = $this->buildVerifyData();
        if (!isset($data['capture_status']))
            return false;
        $valid = $direct ? $direct : $this->isValid($data);
        if ($valid && $data['capture_status'] === 'captured')
            return true;

        return false;
    }

}
