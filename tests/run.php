<?php
/**
 * The PHP SDK's checks: signing and webhooks offline, then (with AFROPIN_TEST_ENV pointing at a JSON file of test keys,
 * base_url, catalog_id and test_pin) a real round trip against an AfroPin API in test mode.
 *   php tests/run.php
 */
spl_autoload_register(fn ($class) => is_file($file = __DIR__ . '/../src/' . str_replace(['AfroPin\\', '\\'], ['', '/'], $class) . '.php') ? require $file : null);

$failed = 0;
function check(string $what, bool $ok): void
{
    global $failed;
    echo ($ok ? '  ok   ' : '  FAIL ') . $what . "\n";
    $failed += $ok ? 0 : 1;
}

echo "offline\n";
$sent = null;
$client = new AfroPin\Client('key_1', 'secret_1', ['base_url' => 'https://example.test/api/v1', 'transport' => function ($method, $url, $headers, $body) use (&$sent) {
    $sent = compact('method', 'url', 'headers', 'body');
    return [200, '{"ok":true,"data":{"pin_id":"p1"}}'];
}]);
$data = $client->reseller->pins->topUp(['pin_id' => 'p1', 'amount' => 500], ['idempotency_key' => 'idem-1']);
$canonical = implode('.', ['POST', 'api/v1/reseller/pins/top-up', $sent['headers']['X-Timestamp'], $sent['headers']['X-Nonce'], hash('sha256', $sent['body'])]);
check('signs METHOD.path.timestamp.nonce.sha256(body)', hash_hmac('sha256', $canonical, 'secret_1') === $sent['headers']['X-Signature']);
check('sends the given Idempotency-Key', $sent['headers']['Idempotency-Key'] === 'idem-1');
check('returns the data', $data === ['pin_id' => 'p1']);
$client->reseller->pins->get('a/b');
check('encodes path values', str_ends_with($sent['url'], '/reseller/pins/a%2Fb') && $sent['body'] === '');
$body = '{"type":"pin.sold","data":{"pin_id":"p1"}}';
check('verifies a webhook', AfroPin\Webhook::verify($body, hash_hmac('sha256', $body, 'whsec'), 'whsec'));
check('refuses a forged webhook', !AfroPin\Webhook::verify($body, hash_hmac('sha256', $body, 'other'), 'whsec'));

$envFile = getenv('AFROPIN_TEST_ENV');
if ($envFile) {
    echo "live (test mode)\n";
    $env = json_decode(file_get_contents($envFile), true);
    $reseller = new AfroPin\Client($env['reseller']['key_id'], $env['reseller']['secret'], ['base_url' => $env['base_url']]);
    $brand = new AfroPin\Client($env['brand']['key_id'], $env['brand']['secret'], ['base_url' => $env['base_url']]);

    check('lists wallets', count($reseller->reseller->wallets->list()) > 0);
    $catalog = $reseller->reseller->catalog->list(['limit' => 5]);
    check('lists the catalog as a page', $catalog instanceof AfroPin\Page && count($catalog) > 0 && isset($catalog->paging['total']));
    $product = $reseller->reseller->catalog->get($env['catalog_id']);
    $amount = (float) ($product['denominations'][0] ?? $product['min']);
    $sold = $reseller->reseller->pins->sell(['catalog_id' => $env['catalog_id'], 'amount' => $amount, 'external_reference' => 'sdk-php-' . bin2hex(random_bytes(6)),
        'first_name' => 'Sdk', 'last_name' => 'Test', 'email' => 'sdk.test@gmail.com']);
    check('sells a PIN with its code', !empty($sold['pin_code']) && !empty($sold['pin_id']));
    $pin = $reseller->reseller->pins->get($sold['pin_id']);
    check('reads it back', (float) $pin['balance'] === $amount && $pin['pin_mask'] === $sold['pin_mask']);
    try {
        $reseller->reseller->pins->get('00000000-0000-4000-8000-000000000000');
        check('an unknown PIN fails', false);
    } catch (AfroPin\AfroPinException $e) {
        check('an unknown PIN fails with pin_not_found (' . $e->status . ')', $e->errorCode === 'pin_not_found' && $e->status === 404);
    }
    $checked = $brand->brand->pins->check(['pin_code' => $env['test_pin']]);
    check('the brand checks a test PIN', array_key_exists('can_redeem', $checked));
}

echo $failed ? "\n$failed failed\n" : "\nall passed\n";
exit($failed ? 1 : 0);
