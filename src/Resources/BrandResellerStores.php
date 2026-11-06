<?php

// Generated from sdks/spec/endpoints.json by sdks/spec/generate.py. Don't edit by hand: change the spec and run it again.

namespace AfroPin\Resources;

use AfroPin\Client;
use AfroPin\Page;

final class BrandResellerStores
{
    public function __construct(private Client $client)
    {
    }

    /**
     * Reseller stores that sell PINs you accept.
     *
     * GET /api/v1/brand/reseller-stores
     *
     * @return Page
     */
    public function list(array $params = [], array $options = [])
    {
        return $this->client->request('GET', 'brand/reseller-stores', $params, $options, true);
    }
}
