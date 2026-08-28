<?php

namespace Flitt;

use PHPUnit\Framework\TestCase;

/**
 * CompanyReports (and Payment::reports(), its backward-compatible alias)
 * authenticate against portal.flitt.com with a merchant-specific
 * application_id/application key - a separate credential pair from the
 * merchant_id/secret_key used everywhere else in this SDK. Merchant 1549902
 * is a shared sandbox dedicated to Reports sample data (application_id
 * '1019', key 'test'), matching the fixture flittpayments/python's own
 * CompanyReportsIntegrationTest uses (tests/data/test_data.json's
 * 'reports_application' entry).
 */
class CompanyReportsTest extends TestCase
{
    /**
     * Merchant dedicated to Reports sandbox examples/tests; it has sample
     * report data attached and is not used for transactions.
     */
    private $reportsMerchantId = 1549902;
    private $reportsApplicationId = '1019';
    private $reportsApplicationKey = 'test';

    /**
     * Baseline: no Reports credentials configured. testGetReturnsReportData()
     * opts into the sandbox credentials itself via
     * setSandboxReportsCredentials().
     */
    protected function setUp(): void
    {
        Configuration::setReportsApplicationId('');
        Configuration::setReportsApplicationKey('');
    }

    private function setSandboxReportsCredentials()
    {
        Configuration::setReportsApplicationId($this->reportsApplicationId);
        Configuration::setReportsApplicationKey($this->reportsApplicationKey);
    }

    /**
     * Plain try/catch rather than expectExceptionMessageMatches(), matching
     * the rest of this suite's convention for asserting on a live API's
     * error message (see SubscriptionTest::testStopOrderNotFound()).
     * @throws Exception\ApiException
     */
    public function testGetRejectsWithoutReportsCredentials()
    {
        try {
            CompanyReports::get(['report_id' => 745, 'on_page' => 10, 'page' => 1]);
            $this->fail('Expected an ApiException for missing Reports credentials');
        } catch (Exception\ApiException $e) {
            $this->assertNotFalse(
                strpos($e->getMessage(), 'application_id/application key are not configured'),
                $e->getMessage()
            );
        }
    }

    /**
     * Payment::reports() must fail the exact same way, since it's a thin
     * alias for CompanyReports::get() rather than a separate implementation.
     * @throws Exception\ApiException
     */
    public function testPaymentReportsAliasRejectsWithoutReportsCredentials()
    {
        try {
            Payment::reports(['report_id' => 745, 'on_page' => 10, 'page' => 1]);
            $this->fail('Expected an ApiException for missing Reports credentials');
        } catch (Exception\ApiException $e) {
            $this->assertNotFalse(
                strpos($e->getMessage(), 'application_id/application key are not configured'),
                $e->getMessage()
            );
        }
    }

    /**
     * Live call against the shared Reports sandbox - mirrors
     * CompanyReportsIntegrationTest.test_reports() in the python SDK.
     * @throws Exception\ApiException
     */
    public function testGetReturnsReportData()
    {
        $this->setSandboxReportsCredentials();
        $result = CompanyReports::get([
            'report_id' => 745,
            'merchant_id' => $this->reportsMerchantId,
            'on_page' => 10,
            'page' => 1,
            'filters' => [
                ['s' => 'order_timestart_from', 'm' => 'from', 'v' => date('Y-m-d', strtotime('-30 days'))],
                ['s' => 'order_timestart_to', 'm' => 'to', 'v' => date('Y-m-d')],
            ],
        ]);

        $this->assertArrayNotHasKey('error', $result);
        $this->assertArrayHasKey('fields', $result);
        $this->assertArrayHasKey('rows_count', $result);
        $this->assertGreaterThanOrEqual(0, $result['rows_count']);
    }
}
