<?php

namespace AfroPin;

/**
 * Checking a webhook AfroPin sent you. Each one is signed with the webhook's secret: the webhook-secret header holds the
 * HMAC-SHA256 of the raw body. Check it against the body exactly as received, before decoding it.
 *
 *   $event = AfroPin\Webhook::parse(file_get_contents('php://input'), $_SERVER['HTTP_WEBHOOK_SECRET'] ?? '', $secret);
 *   if ($event['type'] === 'pin.spent') { ... }
 */
final class Webhook
{
    public static function verify(string $rawBody, string $signature, string $secret): bool
    {
        return $signature !== '' && hash_equals(hash_hmac('sha256', $rawBody, $secret), $signature);
    }

    /**
     * The event, ['type' => 'pin.sold', 'data' => [...]], once its signature checks out.
     *
     * @throws AfroPinException invalid_signature when it doesn't
     */
    public static function parse(string $rawBody, string $signature, string $secret): array
    {
        if (!self::verify($rawBody, $signature, $secret)) {
            throw new AfroPinException('invalid_signature', 'The webhook signature does not match its body');
        }
        $event = json_decode($rawBody, true);
        if (!is_array($event) || !isset($event['type'])) {
            throw new AfroPinException('invalid_response', 'The webhook body is not an AfroPin event');
        }

        return $event;
    }
}
