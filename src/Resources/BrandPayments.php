<?php

// Generated from sdks/spec/endpoints.json by sdks/spec/generate.py. Don't edit by hand: change the spec and run it again.

namespace AfroPin\Resources;

use AfroPin\Client;
use AfroPin\Page;

final class BrandPayments
{
    public function __construct(private Client $client)
    {
    }

    /**
     * The redemptions credited to you, newest first.
     *
     * GET /api/v1/brand/payments
     *
     * @return Page
     */
    public function list(array $params = [], array $options = [])
    {
        return $this->client->request('GET', 'brand/payments', $params, $options, true);
    }

    /**
     * One redemption.
     *
     * GET /api/v1/brand/payments/{transaction_ref}
     *
     * @return mixed
     */
    public function get(string $transactionRef, array $params = [], array $options = [])
    {
        return $this->client->request('GET', 'brand/payments/' . rawurlencode($transactionRef), $params, $options);
    }
}
