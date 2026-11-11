<?php

namespace AfroPin;

/**
 * The AfroPin API client. Signs every request with your key (HMAC-SHA256 over "METHOD.path.timestamp.nonce.sha256(body)"),
 * sends a fresh Idempotency-Key with each one, and returns the response's data. Your key's mode (test or live) decides
 * which records you work with; the base URL is the same for both.
 *
 *   $afropin = new AfroPin\Client('key_id', 'secret');
 *   $pin = $afropin->reseller->pins->sell(['catalog_id' => '...', 'amount' => 5000, ...]);
 */
final class Client
{
    public const VERSION = '2.1.0';
    public const BASE_URL = 'https://afropin.com/api/v1';

    public Reseller $reseller;
    public Brand $brand;

    private string $baseUrl;
    private int $timeout;
    /** @var callable|null fn(string $method, string $url, array $headers, string $body): array{0:int,1:string} */
    private $transport;

    /**
     * @param array{base_url?: string, timeout?: int, transport?: callable} $options
     */
    public function __construct(private string $keyId, private string $secret, array $options = [])
    {
        $this->baseUrl = rtrim($options['base_url'] ?? self::BASE_URL, '/');
        $this->timeout = (int) ($options['timeout'] ?? 30);
        $this->transport = $options['transport'] ?? null;
        $this->reseller = new Reseller($this);
        $this->brand = new Brand($this);
    }

    /**
     * Send one request and return its data, or a Page for lists. $params are the query (GET, DELETE) or the JSON body.
     * $options['idempotency_key'] replaces the generated one, for example to keep it in your own records.
     *
     * Requests are never retried for you: a retried sale with the same external_reference fails safely with
     * duplicate_external_reference, so retry yourself with a new Idempotency-Key when you need to.
     *
     * @throws AfroPinException
     */
    public function request(string $method, string $path, array $params = [], array $options = [], bool $list = false)
    {
        $url = $this->baseUrl . '/' . ltrim($path, '/');
        $body = '';
        if (in_array($method, ['GET', 'DELETE'], true)) {
            if ($params) {
                $url .= '?' . http_build_query($params);
            }
        } else {
            $body = json_encode((object) $params, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        $timestamp = (string) time();
        $nonce = self::uuid();
        $signedPath = ltrim((string) parse_url($url, PHP_URL_PATH), '/');
        $canonical = implode('.', [$method, $signedPath, $timestamp, $nonce, hash('sha256', $body)]);
        $headers = [
            'X-Key-Id' => $this->keyId,
            'X-Timestamp' => $timestamp,
            'X-Nonce' => $nonce,
            'X-Signature' => hash_hmac('sha256', $canonical, $this->secret),
            'Idempotency-Key' => $options['idempotency_key'] ?? self::uuid(),
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'User-Agent' => 'afropin-php/' . self::VERSION,
        ];

        [$status, $raw] = $this->send($method, $url, $headers, $body);
        $json = json_decode($raw, true);
        if (!is_array($json)) {
            throw new AfroPinException('invalid_response', 'The API sent a response that is not JSON', null, $status);
        }
        if (empty($json['ok'])) {
            $error = $json['error'] ?? [];
            throw new AfroPinException((string) ($error['code'] ?? 'unknown_error'), (string) ($error['message'] ?? 'The request failed'), $error['fields'] ?? null, $status);
        }

        return $list ? new Page($json['data'] ?? [], $json['paging'] ?? null) : ($json['data'] ?? null);
    }

    private function send(string $method, string $url, array $headers, string $body): array
    {
        if ($this->transport) {
            return ($this->transport)($method, $url, $headers, $body);
        }
        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_HTTPHEADER => array_map(fn ($name, $value) => $name . ': ' . $value, array_keys($headers), $headers),
            CURLOPT_POSTFIELDS => $body === '' ? null : $body,
        ]);
        $raw = curl_exec($curl);
        if ($raw === false) {
            $message = curl_error($curl);
            curl_close($curl);
            throw new AfroPinException('connection_failed', 'Could not reach the AfroPin API: ' . $message);
        }
        $status = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        curl_close($curl);

        return [$status, (string) $raw];
    }

    private static function uuid(): string
    {
        $bytes = random_bytes(16);
        $bytes[6] = chr(ord($bytes[6]) & 0x0f | 0x40);
        $bytes[8] = chr(ord($bytes[8]) & 0x3f | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
    }
}
