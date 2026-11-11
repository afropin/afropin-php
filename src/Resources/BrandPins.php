<?php

// Generated from sdks/spec/endpoints.json by sdks/spec/generate.py. Don't edit by hand: change the spec and run it again.

namespace AfroPin\Resources;

use AfroPin\Client;
use AfroPin\Page;

final class BrandPins
{
    public function __construct(private Client $client)
    {
    }

    /**
     * Sell a PIN. The response has its full code (pin_code), returned only here.
     *
     * POST /api/v1/brand/pins
     *
     * @return mixed
     */
    public function sell(array $params = [], array $options = [])
    {
        return $this->client->request('POST', 'brand/pins', $params, $options);
    }

    /**
     * One of your PINs: balance, status, masks, customer, family cards.
     *
     * GET /api/v1/brand/pins/{pin_id}
     *
     * @return mixed
     */
    public function get(string $pinId, array $params = [], array $options = [])
    {
        return $this->client->request('GET', 'brand/pins/' . rawurlencode($pinId), $params, $options);
    }

    /**
     * A PIN's movements, newest first.
     *
     * GET /api/v1/brand/pins/{pin_id}/movements
     *
     * @return Page
     */
    public function movements(string $pinId, array $params = [], array $options = [])
    {
        return $this->client->request('GET', 'brand/pins/' . rawurlencode($pinId) . '/movements', $params, $options, true);
    }

    /**
     * Add value to a PIN.
     *
     * POST /api/v1/brand/pins/top-up
     *
     * @return mixed
     */
    public function topUp(array $params = [], array $options = [])
    {
        return $this->client->request('POST', 'brand/pins/top-up', $params, $options);
    }

    /**
     * Block (freeze) a PIN. Its cardholder can't undo it.
     *
     * POST /api/v1/brand/pins/block
     *
     * @return mixed
     */
    public function block(array $params = [], array $options = [])
    {
        return $this->client->request('POST', 'brand/pins/block', $params, $options);
    }

    /**
     * Unblock a PIN.
     *
     * POST /api/v1/brand/pins/unblock
     *
     * @return mixed
     */
    public function unblock(array $params = [], array $options = [])
    {
        return $this->client->request('POST', 'brand/pins/unblock', $params, $options);
    }

    /**
     * Give a PIN a new code; the old one stops working.
     *
     * POST /api/v1/brand/pins/new-code
     *
     * @return mixed
     */
    public function newCode(array $params = [], array $options = [])
    {
        return $this->client->request('POST', 'brand/pins/new-code', $params, $options);
    }

    /**
     * Links that add a PIN to Google Wallet or Apple Wallet.
     *
     * POST /api/v1/brand/pins/wallet-links
     *
     * @return mixed
     */
    public function walletLinks(array $params = [], array $options = [])
    {
        return $this->client->request('POST', 'brand/pins/wallet-links', $params, $options);
    }

    /**
     * Change a PIN's webhook.
     *
     * POST /api/v1/brand/pins/webhook
     *
     * @return mixed
     */
    public function setWebhook(array $params = [], array $options = [])
    {
        return $this->client->request('POST', 'brand/pins/webhook', $params, $options);
    }

    /**
     * Choose who delivers a PIN's codes to its customer: us (handle_delivery true) or you.
     *
     * POST /api/v1/brand/pins/delivery
     *
     * @return mixed
     */
    public function setDelivery(array $params = [], array $options = [])
    {
        return $this->client->request('POST', 'brand/pins/delivery', $params, $options);
    }

    /**
     * Check a PIN before redeeming it; nothing changes.
     *
     * POST /api/v1/brand/pins/check
     *
     * @return mixed
     */
    public function check(array $params = [], array $options = [])
    {
        return $this->client->request('POST', 'brand/pins/check', $params, $options);
    }

    /**
     * Redeem a PIN: take an amount off it and credit your wallet.
     *
     * POST /api/v1/brand/pins/redeem
     *
     * @return mixed
     */
    public function redeem(array $params = [], array $options = [])
    {
        return $this->client->request('POST', 'brand/pins/redeem', $params, $options);
    }
}
