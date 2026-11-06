<?php

// Generated from sdks/spec/endpoints.json by sdks/spec/generate.py. Don't edit by hand: change the spec and run it again.

namespace AfroPin\Resources;

use AfroPin\Client;
use AfroPin\Page;

final class ResellerCashouts
{
    public function __construct(private Client $client)
    {
    }

    /**
     * Request a cashout (only while cashouts are switched on).
     *
     * POST /api/v1/reseller/cashouts
     *
     * @return mixed
     */
    public function request(array $params = [], array $options = [])
    {
        return $this->client->request('POST', 'reseller/cashouts', $params, $options);
    }

    /**
     * Send the customer the confirmation code again.
     *
     * POST /api/v1/reseller/cashouts/send-code
     *
     * @return mixed
     */
    public function sendCode(array $params = [], array $options = [])
    {
        return $this->client->request('POST', 'reseller/cashouts/send-code', $params, $options);
    }

    /**
     * Confirm a cashout with the customer's code.
     *
     * POST /api/v1/reseller/cashouts/{cashout_id}/confirm
     *
     * @return mixed
     */
    public function confirm(string $cashoutId, array $params = [], array $options = [])
    {
        return $this->client->request('POST', 'reseller/cashouts/' . rawurlencode($cashoutId) . '/confirm', $params, $options);
    }

    /**
     * Void an unpaid cashout.
     *
     * POST /api/v1/reseller/cashouts/{cashout_id}/void
     *
     * @return mixed
     */
    public function void(string $cashoutId, array $params = [], array $options = [])
    {
        return $this->client->request('POST', 'reseller/cashouts/' . rawurlencode($cashoutId) . '/void', $params, $options);
    }
}
