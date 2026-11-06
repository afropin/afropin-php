<?php

// Generated from sdks/spec/endpoints.json by sdks/spec/generate.py. Don't edit by hand: change the spec and run it again.

namespace AfroPin\Resources;

use AfroPin\Client;
use AfroPin\Page;

final class BrandQuotes
{
    public function __construct(private Client $client)
    {
    }

    /**
     * Your quotes, newest first.
     *
     * GET /api/v1/brand/quotes
     *
     * @return Page
     */
    public function list(array $params = [], array $options = [])
    {
        return $this->client->request('GET', 'brand/quotes', $params, $options, true);
    }

    /**
     * Quote a business for your gift cards in bulk; returns a pay_url.
     *
     * POST /api/v1/brand/quotes
     *
     * @return mixed
     */
    public function create(array $params = [], array $options = [])
    {
        return $this->client->request('POST', 'brand/quotes', $params, $options);
    }

    /**
     * One quote, by id or QTE- reference.
     *
     * GET /api/v1/brand/quotes/{quote_id}
     *
     * @return mixed
     */
    public function get(string $quoteId, array $params = [], array $options = [])
    {
        return $this->client->request('GET', 'brand/quotes/' . rawurlencode($quoteId), $params, $options);
    }

    /**
     * Withdraw an unpaid quote.
     *
     * POST /api/v1/brand/quotes/{quote_id}/cancel
     *
     * @return mixed
     */
    public function cancel(string $quoteId, array $params = [], array $options = [])
    {
        return $this->client->request('POST', 'brand/quotes/' . rawurlencode($quoteId) . '/cancel', $params, $options);
    }
}
