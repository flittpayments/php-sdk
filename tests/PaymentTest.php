<?php
/**
 * Created by PhpStorm.
 * User: dm
 * Date: 21.05.18
 * Time: 0:15
 */

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
    private $TestCardnon3ds = [
        'card_number' => '4444555511116666',
        'cvv2' => '333',
        'expiry_date' => '1222',
        'required_rectoken' => 'Y'
    ];
    private $TestPcidssData = [
        'currency' => 'GEL',
        'amount' => 1000,
        'client_ip' => '127.2.2.1'
    ];

    /**
     * PaymentTest constructor.
     * @param null $name
     * @param array $data
     * @param string $dataName
     * @throws Exception\ApiException
     */
    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        $this->setTestConfig();
        $this->TestData['rectoken'] = $this->getToken(array_merge($this->TestPcidssData, $this->TestCardnon3ds));
        parent::__construct($name, $data, $dataName);
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

    // No automated test for Payment::reports(): it authenticates against
    // portal.flitt.com with a merchant-specific application_id/application key
    // (contact Flitt support to obtain them; see
    // Configuration::setReportsApplicationId()/setReportsApplicationKey() and
    // https://docs.flitt.com/api/reports/) rather than the shared sandbox
    // merchant_id/secret_key used everywhere else in this suite, so there are
    // no test credentials this SDK can ship to exercise it here. See
    // examples/Payment/reports.php for a usage example.

    /**
     * @param $data
     * @return mixed
     * @throws Exception\ApiException
     */
    private function getToken($data)
    {
        $data = Pcidss::start($data);
        return $data->getData()['rectoken'];
    }
}
