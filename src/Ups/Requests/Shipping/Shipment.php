<?php

namespace XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Shipping;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class Shipment extends Request
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return '/shipments/v1/ship';
    }
}