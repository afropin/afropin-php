<?php

namespace AfroPin;

/**
 * Checking a webhook AfroPin sent you. Each one carries three headers: webhook-id (the same on every retry of one
 * event), webhook-timestamp (Unix seconds, when it was sent) and webhook-signature, the HMAC-SHA256 of
 * "id.timestamp.body" with your webhook's secret. Check it against the body exactly as received, before decoding it.
 * A timestamp more than 5 minutes from now is refused, so a captured webhook can't be replayed later.
 *
 *   $event = AfroPin\Webhook::parse(file_get_contents('php://input'), getallheaders(), $secret);
 *   if ($event['type'] === 'pin.spent') { ... }
 *
 * $headers can be getallheaders(), $_SERVER or a framework's header array ($request->headers->all() in Laravel).
 */
final class Webhook
{
    public const TOLERANCE = 300;

    public static function verify(string $rawBody, array $headers, string $secret, int $tolerance = self::TOLERANCE, ?int $now = null): bool
    {
        try {
            self::check($rawBody, $headers, $secret, $tolerance, $now);

            return true;
        } catch (AfroPinException $e) {
            return false;
        }
    }

    /**
     * The event, ['id' => ..., 'type' => 'pin.sold', 'data' => [...]], once its signature checks out. Use id to skip
     * an event you've already handled.
     *
     * @throws AfroPinException invalid_signature when it doesn't
     */
    public static function parse(string $rawBody, array $headers, string $secret, int $tolerance = self::TOLERANCE): array
    {
        $id = self::check($rawBody, $headers, $secret, $tolerance, null);
        $event = json_decode($rawBody, true);
        if (!is_array($event) || !isset($event['type'])) {
            throw new AfroPinException('invalid_response', 'The webhook body is not an AfroPin event');
        }

        return ['id' => $id] + $event;
    }

    /** The webhook's id, once its signature and timestamp check out */
    private static function check(string $rawBody, array $headers, string $secret, int $tolerance, ?int $now): string
    {
        $headers = self::normalise($headers);
        $id = $headers['webhook-id'] ?? '';
        $timestamp = $headers['webhook-timestamp'] ?? '';
        $signature = $headers['webhook-signature'] ?? '';
        if ($id === '' || !ctype_digit($timestamp) || $signature === '') {
            throw new AfroPinException('invalid_signature', 'The webhook-id, webhook-timestamp or webhook-signature header is missing');
        }
        if (abs(($now ?? time()) - (int) $timestamp) > $tolerance) {
            throw new AfroPinException('invalid_signature', 'The webhook timestamp is too old or too far ahead: check your server clock');
        }
        if (!hash_equals(hash_hmac('sha256', "$id.$timestamp.$rawBody", $secret), $signature)) {
            throw new AfroPinException('invalid_signature', 'The webhook signature does not match its body');
        }

        return $id;
    }

    // Lower-case dashed names, from any of the usual shapes (HTTP_WEBHOOK_ID in $_SERVER, arrays of values)
    private static function normalise(array $headers): array
    {
        $out = [];
        foreach ($headers as $name => $value) {
            $name = str_replace('_', '-', strtolower((string) $name));
            $name = str_starts_with($name, 'http-') ? substr($name, 5) : $name;
            $out[$name] = trim((string) (is_array($value) ? reset($value) : $value));
        }

        return $out;
    }
}
