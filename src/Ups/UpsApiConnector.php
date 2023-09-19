<?php

namespace XtendLunar\Addons\ShippingProviderUps\Ups;

use Illuminate\Support\Facades\Cache;
use Saloon\Http\Connector;
use XtendLunar\Addons\ShippingProviderUps\Ups\Requests\GetAccessToken;

class UpsApiConnector extends Connector
{
    public function resolveBaseUrl(): string
    {
        return "https://wwwcie.ups.com/api";
    }

    public function __construct()
    {
        $this->withTokenAuth($this->getAccessToken());
    }

    protected function getAccessToken(): string
    {
        return Cache::remember('ups_access_token', 60 * 60 * 3, fn() => app(GetAccessToken::class)->send()->json('access_token'));
    }
}