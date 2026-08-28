# Changelog

## 2.1.0

**Added**
- `Flitt\CompanyReports` - Reports moved out of `Payment::reports()` into its
  own top-level class (`Api\Payment\Reports`/`ReportsToken` moved to
  `Api\CompanyReports\Reports`/`ReportsToken` accordingly), matching this
  SDK's [python](https://github.com/flittpayments/python) counterpart as of
  `release_2.1.0`. `Payment::reports()` is kept as a deprecated
  backward-compatible alias for `CompanyReports::get()`; both are now
  covered by a new `tests/CompanyReportsTest.php`, including a live call
  against the shared Reports sandbox (`application_id: '1019'`, key
  `'test'`, merchant `1549902` - the same fixture the python SDK's own
  `CompanyReportsIntegrationTest` uses). See
  [examples/CompanyReports/report.php](examples/CompanyReports/report.php).
- `Configuration::VERSION` - single source of truth for the SDK version,
  used to build the `HttpCurl`/`HttpGuzzle` User-Agent header (`php-sdk/x.y.z`)
  instead of the two independently hardcoded, already-inconsistent
  `'php-sdk'`/`'php-sdk-v2'` strings.

**Breaking**
- Minimum supported PHP raised from `^5.4|^7.0|^8.0` to `^8.2`. CI now tests
  PHP 8.2, 8.3, 8.4 and 8.5 - the branches currently supported per
  [php.net](https://www.php.net/supported-versions.php) - instead of
  5.6-8.1, all of which are end-of-life. `phpunit/phpunit` tightened from
  `~5|^8.5.52|^9.6.33` to `^9.6.33|^10.5.62|^11.5.50|^12.5.8`, dropping the
  unpatched `~5` branch entirely - this closes
  [CVE-2026-24765 / GHSA-vvj3-c3rp-c85p](https://github.com/sebastianbergmann/phpunit/security/advisories/GHSA-vvj3-c3rp-c85p)
  for real (it could only be dismissed as inapplicable before, since `~5`
  was required to test PHP 5.6/7.0/7.1 in CI).
- Test suite modernized for PHPUnit 10-12: `TestCase::__construct()` is
  `final` as of a recent PHPUnit, so every test class's redundant
  constructor override was removed; the two that did real work (fetching a
  live rectoken / creating a live order, each shared across several test
  methods) moved to `setUpBeforeClass()`; `ResultHelperTest`'s
  `@dataProvider` docblock (silently ignored by PHPUnit 10+, which requires
  the `#[DataProvider]` attribute instead) converted accordingly.
- `Exception\MainException` (base of `ApiException`/`HttpClientException`):
  removed `getFondyCode()` (a leftover from the pre-rebrand "Fondy" name;
  it also didn't return a code - it returned the rebuilt message string)
  and `getHttpBody()` (dead code - the backing property was never
  populated, so it always returned `null`). Added `getHttpStatus()`
  (correctly returns the HTTP status the request failed with) and
  `getPspErrorCode()` (returns the API's `error_code` field, if any).
  `getRequestId()` is unchanged in signature but now actually returns
  just the `request_id` value instead of the whole rebuilt message.

**Security**
- `Helper\ResultHelper::isPaymentValid()` now compares the callback/response
  signature with `hash_equals()` (constant-time) instead of `===`, closing
  a timing-attack window on signature verification (CWE-208).
- `examples/resultTest.php` built its target URL from
  `$_SERVER['HTTP_HOST']`/`SERVER_PORT` (attacker-controlled via the Host
  header) and used it as a server-side cURL target - an SSRF pattern.
  Hardcoded to `http://localhost:8000`, matching the dev server the README
  tells you to run these examples against.

**Fixed**
- `HttpGuzzle::request()` rebuilt `$guzzleHeaders` from scratch on every
  loop iteration instead of merging, so only the last of multiple headers
  (e.g. `Content-Type` alongside `Authorization`) was ever actually sent.
  Also no longer truncates a header value that itself contains a `:`.
- `HttpCurl::request()` threw before `curl_close($ch)` on a non-200
  response, leaking the handle.
- `Helper\ValidationHelper::validateRequiredParams()` recursed into a
  nested required-params array before confirming the key existed in
  `$params`, so a missing top-level key on a nested requirement raised a
  raw `TypeError` instead of the intended `InvalidArgumentException`.
- `Helper\ApiHelper::toFormData()` passed `NULL` to `http_build_query()`'s
  `$numeric_prefix` parameter, deprecated as of PHP 8.3.
- `ConfigurationTest::testSetHttpClient()`: the notice-expectation approach
  it used (`expectNotice()`/`PHPUnit_Framework_Error_Notice`) was removed
  in PHPUnit 10 with no direct replacement; rewritten to suppress and
  assert on the actual fallback behavior instead - it had also always
  incorrectly asserted `assertFalse` on a call that returns an `HttpCurl`
  instance.

**Changed**
- `composer.json` / `README.md` authorship updated from an individual
  contributor's name and personal email to `Flitt`.

**Removed**
- `111.php`, `init.php`, `result.php`: unreferenced ad-hoc scratch scripts
  at the repo root left over from the very first commit, hardcoding
  sandbox credentials and superseded by the real test suite / `examples/`.

## 2.0.0

**Breaking**
- Removed XML content-type support (`Configuration::setRequestType()` only ever
  accepted `'json'`/`'form'`; the leftover `'xml'` branches in `Api::converDataV1()`
  and `Result::formatResult()` called methods that no longer exist and were
  unreachable through the public API). XML is no longer documented on
  [docs.flitt.com](https://docs.flitt.com/) - only `json` and `form` are supported.
- Removed `Order::transactionList()` / `Api\Order\TransactionList` and
  `OrderResponse::isCapturedByList()` - no longer supported.
- Replaced `Order::atolLogs()` / `Api\Order\Atol` (its `/get_atol_logs/` endpoint
  now returns HTTP 404) with `Order::fiscalData()` / `Api\Order\FiscalData`,
  hitting the current `POST /api/fiscal_data` endpoint - see
  [docs.flitt.com/api/fiscal_data](https://docs.flitt.com/api/fiscal_data/).
- Rewrote `Payment::reports()` / `Api\Payment\Reports` to match the current
  reports API on `portal.flitt.com` (bearer-token auth via a new
  `Api\Payment\ReportsToken`, `filters`/`report_id`/`on_page`/`page` params)
  instead of the old, differently-shaped `pay.flitt.com/api/reports/` call.
  Requires new merchant-specific credentials via
  `Configuration::setReportsApplicationId()`/`setReportsApplicationKey()` -
  see [docs.flitt.com/api/reports](https://docs.flitt.com/api/reports/). Now
  returns the decoded report data directly instead of wrapping it in `Response`.

**Added**
- IBAN withdrawal: `Flitt\IbanCredit::credit()` — see
  [docs.flitt.com/api/create-order-ibancredit](https://docs.flitt.com/api/create-order-ibancredit/).
- Open Banking / Installments deeplink creation: `Flitt\Checkout::deeplink()` — see
  [docs.flitt.com/api/bank-app-deeplinks](https://docs.flitt.com/api/bank-app-deeplinks/).

**Fixed**
- `HttpGuzzle` client's cURL options (TLS verification, timeouts, user-agent) were
  never actually applied due to a malformed options array.
- `phpunit/phpunit` dev dependency floor raised to close
  [CVE-2026-24765 / GHSA-vvj3-c3rp-c85p](https://github.com/sebastianbergmann/phpunit/security/advisories/GHSA-vvj3-c3rp-c85p).

## 1.0.1 and earlier

See git history.
