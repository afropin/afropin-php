<?php

// Generated from sdks/spec/endpoints.json by sdks/spec/generate.py. Don't edit by hand: change the spec and run it again.

namespace AfroPin\Resources;

use AfroPin\Client;
use AfroPin\Page;

final class BrandCatalog
{
    public function __construct(private Client $client)
    {
    }

    /**
     * The products you accept.
     *
     * GET /api/v1/brand/catalog
     *
     * @return Page
     */
    public function list(array $params = [], array $options = [])
    {
        return $this->client->request('GET', 'brand/catalog', $params, $options, true);
    }

    /**
     * One product you accept.
     *
     * GET /api/v1/brand/catalog/{catalog_id}
     *
     * @return mixed
     */
    public function get(string $catalogId, array $params = [], array $options = [])
    {
        return $this->client->request('GET', 'brand/catalog/' . rawurlencode($catalogId), $params, $options);
    }
}
