# AfroPin for PHP

The AfroPin gift card API from PHP: sell, top up and redeem PINs, quotes, stores and webhooks.

```
composer require afropin/afropin-php
```

PHP 8.0 or newer, with the curl extension. No other dependencies.

## Start

```php
$afropin = new AfroPin\Client(getenv('AFROPIN_KEY_ID'), getenv('AFROPIN_SECRET'));
```

Your API key's mode decides whether you work with test or live records; the address is the same for both. Keep the
secret on your server, never in a browser or app.

### Reseller: sell a PIN

```php
$pin = $afropin->reseller->pins->sell([
    'catalog_id' => $catalogId,          // from $afropin->reseller->catalog->list()
    'amount' => 5000,
    'external_reference' => $orderId,    // your own reference; reusing it fails safely
    'first_name' => 'Ada',
    'last_name' => 'Obi',
    'email' => 'ada@example.com',
]);

$pin['pin_code'];   // the full code: returned only here, so deliver it to your customer now
```

### Brand: check and redeem a PIN

```php
$check = $afropin->brand->pins->check(['pin_code' => $code, 'amount' => 2500]);
if ($check['can_redeem']) {
    $afropin->brand->pins->redeem(['pin_code' => $code, 'amount' => 2500, 'external_reference' => $saleId]);
}
```

Every call is listed in [METHODS.md](../METHODS.md). Parameters and response fields are the API's own, as in the
developer docs.

## Errors

```php
try {
    $afropin->reseller->pins->topUp(['pin_id' => $pinId, 'amount' => 1000, 'external_reference' => $ref]);
} catch (AfroPin\AfroPinException $e) {
    $e->errorCode;  // for example pin_not_found, amount_too_large, duplicate_external_reference
    $e->fields;     // per-field messages for input problems
    $e->status;     // HTTP status, or 0 when the API wasn't reached (connection_failed)
}
```

Branch on `errorCode`; the message is for people and may change.

## Lists

Lists return an `AfroPin\Page`: iterate it, and read `paging` for `page`, `pages` and `total`.

```php
$page = $afropin->brand->payments->list(['limit' => 50]);
foreach ($page as $payment) { ... }
if ($page->hasMore()) {
    $next = $afropin->brand->payments->list(['limit' => 50, 'page' => $page->paging['page'] + 1]);
}
```

## Idempotency and retries

Each request gets a new `Idempotency-Key`. To use your own, pass it as the last argument:
`->sell($params, ['idempotency_key' => $key])`. Nothing is retried for you. If a sale times out, send it again with
the same `external_reference` and a new key: it either goes through once, or fails with `duplicate_external_reference`.

## Webhooks

```php
$event = AfroPin\Webhook::parse(file_get_contents('php://input'), $_SERVER['HTTP_WEBHOOK_SECRET'] ?? '', $webhookSecret);

if ($event['type'] === 'pin.spent') {
    recordSpend($event['data']);   // your own code
}
```

`parse` throws `invalid_signature` when the signature doesn't match. Use the raw body exactly as received.
