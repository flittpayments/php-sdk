<?php

namespace Flitt;

use Flitt\Api\CompanyReports as Api;

/**
 * Client for the separate Flitt Company Reports service (portal.flitt.com).
 * This uses its own application_id/application key credential pair, not the
 * merchant_id/secret_key used everywhere else in this SDK - see
 * Configuration::setReportsApplicationId()/setReportsApplicationKey() and
 * https://docs.flitt.com/api/reports/.
 *
 * Moved out of Payment::reports() into its own top-level class, matching
 * this SDK's convention of one facade class per API surface (Order,
 * IbanCredit, P2pcredit, ...) and this endpoint's separate host/credentials.
 * Payment::reports() is kept as a backward-compatible alias for existing
 * integrations.
 *
 * @package Flitt
 */
class CompanyReports
{
    /**
     * Fetch a company report.
     * @see https://docs.flitt.com/api/reports/
     * @param $data must include 'report_id', plus optional 'filters'/'on_page'/'page'/'merchant_id'
     * @return array decoded report response ({data, fields, rows_count, rows_page, rows_on_page})
     * @throws Exception\ApiException
     */
    public static function get($data)
    {
        $api = new Api\Reports();
        return $api->get($data);
    }

    /**
     * Alias for get(), kept for naming parity with this SDK's other
     * language bindings.
     * @param $data
     * @return array
     * @throws Exception\ApiException
     */
    public static function reports($data)
    {
        return self::get($data);
    }
}
