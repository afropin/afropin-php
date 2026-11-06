<?php

// Generated from sdks/spec/endpoints.json by sdks/spec/generate.py. Don't edit by hand: change the spec and run it again.

namespace AfroPin\Resources;

use AfroPin\Client;
use AfroPin\Page;

final class BrandWallets
{
    public function __construct(private Client $client)
    {
    }

    /**
     * Your wallets and their balances, one per currency.
     *
     * GET /api/v1/brand/wallets
     *
     * @return Page
     */
    public function list(array $params = [], array $options = [])
    {
        return $this->client->request('GET', 'brand/wallets', $params, $options, true);
    }
}
