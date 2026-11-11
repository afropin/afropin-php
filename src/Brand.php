<?php

// Generated from sdks/spec/endpoints.json by sdks/spec/generate.py. Don't edit by hand: change the spec and run it again.

namespace AfroPin;

/** The brand API (/api/v1/brand): every call a brand account can make, by area. */
final class Brand
{
    public Resources\BrandWallets $wallets;
    public Resources\BrandStores $stores;
    public Resources\BrandCards $cards;
    public Resources\BrandIssuingCatalog $issuingCatalog;
    public Resources\BrandBrandStores $brandStores;
    public Resources\BrandPins $pins;
    public Resources\BrandCatalog $catalog;
    public Resources\BrandResellerStores $resellerStores;
    public Resources\BrandPayments $payments;
    public Resources\BrandQuotes $quotes;

    public function __construct(Client $client)
    {
        $this->wallets = new Resources\BrandWallets($client);
        $this->stores = new Resources\BrandStores($client);
        $this->cards = new Resources\BrandCards($client);
        $this->issuingCatalog = new Resources\BrandIssuingCatalog($client);
        $this->brandStores = new Resources\BrandBrandStores($client);
        $this->pins = new Resources\BrandPins($client);
        $this->catalog = new Resources\BrandCatalog($client);
        $this->resellerStores = new Resources\BrandResellerStores($client);
        $this->payments = new Resources\BrandPayments($client);
        $this->quotes = new Resources\BrandQuotes($client);
    }
}
