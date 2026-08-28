<?php

namespace Flitt;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Covers ResultHelper::isPaymentValid() signature verification, in
 * particular the constant-time comparison fix (hash_equals() instead of
 * a naive '=='/'===' string comparison). These are pure local checks -
 * no network calls - since generateSignature()/isPaymentValid() are both
 * deterministic given a secret key.
 */
class ResultHelperTest extends TestCase
{
    private $secretKey = 'test_secret_key';

    #[DataProvider('apiVersionProvider')]
    public function testValidSignatureIsAccepted($version)
    {
        $result = $this->buildSignedResult($version, []);
        $this->assertTrue(Helper\ResultHelper::isPaymentValid($result, $this->secretKey, $version));
    }

    #[DataProvider('apiVersionProvider')]
    public function testTamperedSignatureIsRejected($version)
    {
        $result = $this->buildSignedResult($version, ['signature' => 'not_a_real_signature']);
        $this->assertFalse(Helper\ResultHelper::isPaymentValid($result, $this->secretKey, $version));
    }

    #[DataProvider('apiVersionProvider')]
    public function testWrongSecretKeyIsRejected($version)
    {
        $result = $this->buildSignedResult($version, []);
        $this->assertFalse(Helper\ResultHelper::isPaymentValid($result, 'a_different_secret', $version));
    }

    /**
     * A signature that is correct except for its last character must still
     * be rejected - guards against a comparison that only checks a prefix
     * (e.g. an off-by-one loop bound in a hand-rolled constant-time compare).
     */
    #[DataProvider('apiVersionProvider')]
    public function testSignatureDifferingInLastCharacterIsRejected($version)
    {
        $result = $this->buildSignedResult($version, []);
        $result['signature'][strlen($result['signature']) - 1] =
            $result['signature'][strlen($result['signature']) - 1] === 'a' ? 'b' : 'a';
        $this->assertFalse(Helper\ResultHelper::isPaymentValid($result, $this->secretKey, $version));
    }

    public function testMissingSignatureKeyIsRejected()
    {
        $this->assertFalse(Helper\ResultHelper::isPaymentValid(['order_id' => 'x'], $this->secretKey, '1.0'));
    }

    public static function apiVersionProvider()
    {
        return [
            'v1.0' => ['1.0'],
            'v2.0' => ['2.0'],
        ];
    }

    /**
     * @param string $version
     * @param array $overrides applied after signing, to build an invalid result
     * @return array
     */
    private function buildSignedResult($version, array $overrides)
    {
        if ($version === '2.0') {
            $encodedData = base64_encode(Helper\ApiHelper::toJSON(['order' => [
                'order_id' => 'test_order_1',
                'amount' => 111,
                'currency' => 'GEL',
            ]]));
            $result = [
                'encodedData' => $encodedData,
                'signature' => Helper\ApiHelper::generateSignature($encodedData, $this->secretKey, $version),
            ];
        } else {
            $params = [
                'order_id' => 'test_order_1',
                'amount' => 111,
                'currency' => 'GEL',
                'order_status' => 'approved',
            ];
            $result = $params;
            $result['signature'] = Helper\ApiHelper::generateSignature($params, $this->secretKey, $version);
        }

        return array_merge($result, $overrides);
    }
}
