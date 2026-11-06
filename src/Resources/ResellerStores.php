<?php

// Generated from sdks/spec/endpoints.json by sdks/spec/generate.py. Don't edit by hand: change the spec and run it again.

namespace AfroPin\Resources;

use AfroPin\Client;
use AfroPin\Page;

final class ResellerStores
{
    public function __construct(private Client $client)
    {
    }

    /**
     * Your stores.
     *
     * GET /api/v1/reseller/stores
     *
     * @return Page
     */
    public function list(array $params = [], array $options = [])
    {
        return $this->client->request('GET', 'reseller/stores', $params, $options, true);
    }

    /**
     * Add a store.
     *
     * POST /api/v1/reseller/stores
     *
     * @return mixed
     */
    public function create(array $params = [], array $options = [])
    {
        return $this->client->request('POST', 'reseller/stores', $params, $options);
    }

    /**
     * Edit a store.
     *
     * POST /api/v1/reseller/stores/{store_id}
     *
     * @return mixed
     */
    public function update(string $storeId, array $params = [], array $options = [])
    {
        return $this->client->request('POST', 'reseller/stores/' . rawurlencode($storeId), $params, $options);
    }

    /**
     * Remove a store.
     *
     * DELETE /api/v1/reseller/stores/{store_id}
     *
     * @return mixed
     */
    public function delete(string $storeId, array $params = [], array $options = [])
    {
        return $this->client->request('DELETE', 'reseller/stores/' . rawurlencode($storeId), $params, $options);
    }
}
