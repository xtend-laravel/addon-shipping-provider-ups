<?php

namespace XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Shipping;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class Shipment extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return '/shipments/v1/ship';
    }
}
