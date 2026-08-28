<?php

namespace Flitt;

use PHPUnit\Framework\TestCase;

class IbanCreditTest extends TestCase
{
    private $mid = 1549901;
    private $CreditKey = 'testcredit';
    private $request_types = ['json', 'form'];
    // Test IBAN from https://docs.flitt.com/api/testing/ (status: processing). Of the
    // three published there, only this one satisfies the real ISO 7064 MOD-97 checksum
    // validated client-side (see ValidationHelper::validateIban()) - GE00TB...0001
    // (approved) and GE00TB...0002 (declined) do not, so they can't be used here.
    private $TestData = [
        'currency' => 'GEL',
        'amount' => 1000,
        'receiver_iban' => 'GE00TB0000000000000003'
    ];

    // Deliberately blank - proves IbanCredit::credit() signs/authenticates with
    // the credit key, not the purchase secret key (see testCredit() and
    // testCreditRequiresCorrectCreditKey() below).
    private function setTestConfig()
    {
        Configuration::setMerchantId($this->mid);
        Configuration::setSecretKey('');
        Configuration::setCreditKey($this->CreditKey);
        Configuration::setApiVersion('1.0');
    }

    /**
     * Succeeds with the purchase secret key blanked out, using only the credit
     * key - confirms IbanCredit::credit() authenticates as a credit/payout
     * operation, not a purchase.
     * @throws Exception\ApiException
     */
    public function testCredit()
    {
        $this->setTestConfig();
        foreach ($this->request_types as $type) {
            Configuration::setRequestType($type);
            $result = IbanCredit::credit($this->TestData);
            $this->validateResult($result->getData());
            $this->isValid($result->isValid());
        }
    }

    /**
     * Conversely, a wrong credit key must fail signature validation - proves
     * the credit key's value, not just its presence, actually drives the
     * request signature.
     */
    public function testCreditRequiresCorrectCreditKey()
    {
        Configuration::setMerchantId($this->mid);
        Configuration::setSecretKey('');
        Configuration::setCreditKey('wrong_credit_key');
        Configuration::setApiVersion('1.0');
        Configuration::setRequestType('json');
        try {
            IbanCredit::credit($this->TestData);
            $this->fail('Expected an ApiException for an invalid credit key');
        } catch (Exception\ApiException $e) {
            $this->assertNotFalse(strpos($e->getMessage(), 'Invalid signature'), $e->getMessage());
        }
    }

    /**
     * An invalid receiver_iban must be rejected client-side before any network call
     */
    public function testCreditRejectsInvalidIban()
    {
        $this->setTestConfig();
        Configuration::setRequestType('json');
        $this->expectException(\InvalidArgumentException::class);
        IbanCredit::credit([
            'currency' => 'GEL',
            'amount' => 1000,
            'receiver_iban' => 'NOT-AN-IBAN'
        ]);
    }

    private function validateResult($result)
    {
        $this->assertNotEmpty($result['order_id'], 'order_id is empty');
        $this->assertNotEmpty($result['payment_id'], 'payment_id is empty');
        $this->assertEquals($result['response_status'], 'success');
    }

    private function isValid($result)
    {
        $this->assertEquals($result, true);
    }
}
