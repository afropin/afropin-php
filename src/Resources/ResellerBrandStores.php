<?php

// Generated from sdks/spec/endpoints.json by sdks/spec/generate.py. Don't edit by hand: change the spec and run it again.

namespace AfroPin\Resources;

use AfroPin\Client;
use AfroPin\Page;

final class ResellerBrandStores
{
    public function __construct(private Client $client)
    {
    }

    /**
     * Brand stores where a PIN of one product can be spent: pass catalog_id.
     *
     * GET /api/v1/reseller/brand-stores
     *
     * @return Page
     */
    public function list(array $params = [], array $options = [])
    {
        return $this->client->request('GET', 'reseller/brand-stores', $params, $options, true);
    }
}
