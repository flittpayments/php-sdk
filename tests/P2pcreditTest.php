<?php

namespace Flitt;

use PHPUnit\Framework\TestCase;

class P2pcreditTest extends TestCase
{
    private $mid = 1549901;
    private $CreditKey = 'testcredit';
    private $request_types = ['json', 'form'];
    private $TestData = [
        'currency' => 'GEL',
        'amount' => 111,
        'receiver_card_number' => '4444555511116666'
    ];

    private function setTestConfig()
    {
        Configuration::setMerchantId($this->mid);
        Configuration::setSecretKey('');
        Configuration::setCreditKey($this->CreditKey);
        Configuration::setApiVersion('1.0');
    }

    /**
     * @throws Exception\ApiException
     */
    public function testCredit()
    {
        $this->setTestConfig();
        foreach ($this->request_types as $type) {
            Configuration::setRequestType($type);
            $result = P2pcredit::start($this->TestData);
            $this->validateResult($result->getData());
            $this->isValid($result->isValid());
        }
    }

    /**
     * P2P credit using a rectoken obtained from a prior purchase, rather than
     * a raw receiver_card_number - see examples/P2pcredit/p2pcredit_rectoken.php.
     * @throws Exception\ApiException
     */
    public function testCreditWithReceiverRectoken()
    {
        Configuration::setMerchantId($this->mid);
        Configuration::setSecretKey('test');
        Configuration::setApiVersion('1.0');
        Configuration::setRequestType('json');
        $purchase = Pcidss::start([
            'currency' => 'GEL',
            'amount' => 1000,
            'client_ip' => '127.2.2.1',
            'card_number' => '4444555511116666',
            'cvv2' => '333',
            'expiry_date' => '1222',
            'required_rectoken' => 'Y'
        ]);
        $rectoken = $purchase->getData()['rectoken'];
        $this->assertNotEmpty($rectoken, 'rectoken is empty');

        $this->setTestConfig();
        $result = P2pcredit::start([
            'currency' => 'GEL',
            'amount' => 500,
            'receiver_rectoken' => $rectoken
        ]);
        $this->validateResult($result->getData());
        $this->isValid($result->isValid());
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
