<?php

namespace XtendLunar\Addons\ShippingProviderUps\Ups\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\SoloRequest;
use Saloon\Traits\Body\HasFormBody;

class GetAccessToken extends SoloRequest implements HasBody
{
    use HasFormBody;

    protected Method $method = Method::POST;

    public function __construct(protected string $clientId, protected string $clientSecret)
    {
    }

    public function resolveEndpoint(): string
    {
        return config('ups.is_sandbox') ? 'https://wwwcie.ups.com/security/v1/oauth/token' : 'https://onlinetools.ups.com/security/v1/oauth/token';
    }

    protected function defaultBody(): array
    {
        return ['grant_type' => 'client_credentials'];
    }

    protected function defaultHeaders(): array
    {
        return [
            'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
            'x-merchant-id' => $this->clientId,
            'accept'        => 'application/json',
        ];
    }
}
