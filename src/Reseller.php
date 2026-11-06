<?php

// Generated from sdks/spec/endpoints.json by sdks/spec/generate.py. Don't edit by hand: change the spec and run it again.

namespace AfroPin;

/** The reseller API (/api/v1/reseller): every call a reseller account can make, by area. */
final class Reseller
{
    public Resources\ResellerWallets $wallets;
    public Resources\ResellerStores $stores;
    public Resources\ResellerCards $cards;
    public Resources\ResellerCatalog $catalog;
    public Resources\ResellerPins $pins;
    public Resources\ResellerBrandStores $brandStores;
    public Resources\ResellerCashouts $cashouts;

    public function __construct(Client $client)
    {
        $this->wallets = new Resources\ResellerWallets($client);
        $this->stores = new Resources\ResellerStores($client);
        $this->cards = new Resources\ResellerCards($client);
        $this->catalog = new Resources\ResellerCatalog($client);
        $this->pins = new Resources\ResellerPins($client);
        $this->brandStores = new Resources\ResellerBrandStores($client);
        $this->cashouts = new Resources\ResellerCashouts($client);
    }
}
