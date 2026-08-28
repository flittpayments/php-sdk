<?php

namespace Flitt;

use PHPUnit\Framework\TestCase;

class PaymentTest extends TestCase
{
    private $mid = 1549901;
    private $Secret = 'test';
    private $request_types = ['json', 'form'];
    private $TestData = [
        'currency' => 'GEL',
        'amount' => 111,
        'rectoken' => ''
    ];
    private static $TestCardnon3ds = [
        'card_number' => '4444555511116666',
        'cvv2' => '333',
        'expiry_date' => '1222',
        'required_rectoken' => 'Y'
    ];
    private static $TestPcidssData = [
        'currency' => 'GEL',
        'amount' => 1000,
        'client_ip' => '127.2.2.1'
    ];

    /**
     * @var string rectoken shared by every test in this class - fetched once
     * via setUpBeforeClass() rather than per-test, since it requires a live
     * API call.
     */
    private static $sharedRectoken;

    /**
     * @throws Exception\ApiException
     */
    public static function setUpBeforeClass(): void
    {
        Configuration::setMerchantId(1549901);
        Configuration::setSecretKey('test');
        $result = Pcidss::start(array_merge(self::$TestPcidssData, self::$TestCardnon3ds));
        self::$sharedRectoken = $result->getData()['rectoken'];
    }

    protected function setUp(): void
    {
        $this->setTestConfig();
        $this->TestData['rectoken'] = self::$sharedRectoken;
    }

    /**
     * Setting test config
     */
    private function setTestConfig()
    {
        Configuration::setMerchantId($this->mid);
        Configuration::setSecretKey($this->Secret);

    }

    /**
     * @throws Exception\ApiException
     */
    public function testRecurring()
    {
        $this->setTestConfig();
        Configuration::setApiVersion('1.0');
        foreach ($this->request_types as $type) {
            Configuration::setRequestType($type);
            $result = Payment::recurring($this->TestData);
            $this->assertEquals($result->isApproved(), true);
            $this->assertEquals($result->isValid(), true);
            $this->assertEquals($result->getData()['response_status'], 'success');
        }
    }

    /**
     * @throws Exception\ApiException
     */
    public function testRecurringv2()
    {
        $this->setTestConfig();
        Configuration::setApiVersion('2.0');
        Configuration::setRequestType('json');
        $result = Payment::recurring($this->TestData);
        $this->assertEquals($result->isApproved(), true);
        $this->assertEquals($result->isValid(), true);
        $this->assertEquals($result->getData()['response_status'], 'success');

    }

    // CompanyReports::get() (Payment::reports() is a deprecated alias for it)
    // authenticates against portal.flitt.com with a merchant-specific
    // application_id/application key - see
    // Configuration::setReportsApplicationId()/setReportsApplicationKey() and
    // https://docs.flitt.com/api/reports/ - rather than the shared sandbox
    // merchant_id/secret_key used everywhere else in this suite. It's covered
    // separately in tests/CompanyReportsTest.php, via a shared sandbox
    // reports application dedicated to that endpoint. See
    // examples/CompanyReports/report.php for a usage example against your
    // own production credentials.
}
