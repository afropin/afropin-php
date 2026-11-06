<?php

// Generated from sdks/spec/endpoints.json by sdks/spec/generate.py. Don't edit by hand: change the spec and run it again.

namespace AfroPin\Resources;

use AfroPin\Client;
use AfroPin\Page;

final class BrandCards
{
    public function __construct(private Client $client)
    {
    }

    /**
     * Look up one of your printed cards before activating it.
     *
     * POST /api/v1/brand/cards/lookup
     *
     * @return mixed
     */
    public function lookup(array $params = [], array $options = [])
    {
        return $this->client->request('POST', 'brand/cards/lookup', $params, $options);
    }

    /**
     * Activate a printed card: sells a PIN for the amount loaded.
     *
     * POST /api/v1/brand/cards/activate
     *
     * @return mixed
     */
    public function activate(array $params = [], array $options = [])
    {
        return $this->client->request('POST', 'brand/cards/activate', $params, $options);
    }
}
