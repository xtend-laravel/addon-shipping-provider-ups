<?php

namespace XtendLunar\Addons\ShippingProviderUps\Ups;

use Illuminate\Support\Facades\Cache;
use Saloon\Http\Connector;
use XtendLunar\Addons\ShippingProviderUps\Ups\Requests\GetAccessToken;

class UpsApiConnector extends Connector
{
    protected string $productionBaseUrl = 'https://onlinetools.ups.com/api';
    protected string $sandboxBaseUrl = 'https://wwwcie.ups.com/api';

    public function resolveBaseUrl(): string
    {
        return config('ups.is_sandbox') ? $this->sandboxBaseUrl : $this->productionBaseUrl;
    }

    public function __construct()
    {
        $this->withTokenAuth($this->getAccessToken());
    }

    protected function getAccessToken(): string
    {
        Cache::forget('ups_access_token');
        return Cache::remember('ups_access_token', 60 * 60 * 3, fn() => app(GetAccessToken::class)->send()->json('access_token'));
    }
}
