<?php

// Generated from sdks/spec/endpoints.json by sdks/spec/generate.py. Don't edit by hand: change the spec and run it again.

namespace AfroPin\Resources;

use AfroPin\Client;
use AfroPin\Page;

final class ResellerCards
{
    public function __construct(private Client $client)
    {
    }

    /**
     * Look up one of your printed cards before activating it.
     *
     * POST /api/v1/reseller/cards/lookup
     *
     * @return mixed
     */
    public function lookup(array $params = [], array $options = [])
    {
        return $this->client->request('POST', 'reseller/cards/lookup', $params, $options);
    }

    /**
     * Activate a printed card: sells a PIN for the amount loaded.
     *
     * POST /api/v1/reseller/cards/activate
     *
     * @return mixed
     */
    public function activate(array $params = [], array $options = [])
    {
        return $this->client->request('POST', 'reseller/cards/activate', $params, $options);
    }
}
