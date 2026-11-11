<?php

// Generated from sdks/spec/endpoints.json by sdks/spec/generate.py. Don't edit by hand: change the spec and run it again.

namespace AfroPin\Resources;

use AfroPin\Client;
use AfroPin\Page;

final class BrandBrandStores
{
    public function __construct(private Client $client)
    {
    }

    /**
     * Brand stores where a PIN of one of your products can be spent: pass catalog_id.
     *
     * GET /api/v1/brand/brand-stores
     *
     * @return Page
     */
    public function list(array $params = [], array $options = [])
    {
        return $this->client->request('GET', 'brand/brand-stores', $params, $options, true);
    }
}
