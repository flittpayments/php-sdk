<?php

namespace Flitt;

use PHPUnit\Framework\TestCase;

class SubscriptionTest extends TestCase
{
    private $mid = 1549901;
    private $secret_key = 'test';
    private $TestSubscriptionData;

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        // start_time must be today or in the future, so compute it at run time
        // rather than hardcoding a date that will eventually fall in the past.
        $this->TestSubscriptionData = [
            'currency' => 'GEL',
            'amount' => 10000,
            'recurring_data' => [
                'start_time' => date('Y-m-d', strtotime('+1 day')),
                'amount' => 1000,
                'every' => 30,
                'period' => 'day',
                'state' => 'y',
                'readonly' => 'y'
            ]
        ];
        parent::__construct($name, $data, $dataName);
    }

    private function setTestConfig()
    {
        Configuration::setMerchantId($this->mid);
        Configuration::setSecretKey($this->secret_key);
        Configuration::setRequestType('json');
        Configuration::setApiVersion('2.0');
    }

    /**
     * @throws Exception\ApiException
     */
    public function testSubscriptionToken()
    {
        $this->setTestConfig();
        $result = Subscription::token($this->TestSubscriptionData)->getData();
        $this->assertNotEmpty($result['token'], 'payment_id is empty');
    }

    /**
     * @throws Exception\ApiException
     */
    public function testSubscriptionUrl()
    {
        $this->setTestConfig();
        $result = Subscription::url($this->TestSubscriptionData)->getData();
        $this->validate($result);

    }

    /**
     * @param $result
     */
    private function validate($result)
    {
        $this->assertNotEmpty($result['checkout_url'], 'checkout_url is empty');
        $this->assertNotEmpty($result['payment_id'], 'payment_id is empty');
    }

    /**
     * A calendar subscription only becomes a real order once a customer
     * completes the checkout it was issued for, which can't be automated here
     * (it needs a real browser session). This confirms the request wiring for
     * Subscription::stop() is correct by asserting the deterministic error the
     * sandbox returns for an order that was never completed. Uses a plain
     * try/catch rather than expectExceptionMessageMatches(), which isn't
     * available on the phpunit ~5 branch this SDK still supports.
     * @throws Exception\ApiException
     */
    public function testStopOrderNotFound()
    {
        $this->setTestConfig();
        try {
            Subscription::stop('nonexistent_order_' . time());
            $this->fail('Expected an ApiException for a nonexistent order');
        } catch (Exception\ApiException $e) {
            $this->assertNotFalse(strpos($e->getMessage(), 'Order not found'), $e->getMessage());
        }
    }
}
