# Changelog

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
