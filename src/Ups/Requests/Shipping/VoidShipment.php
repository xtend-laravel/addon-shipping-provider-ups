<?php

namespace XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Shipping;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class VoidShipment extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::DELETE;

    public function resolveEndpoint(): string
    {
        return '/shipments/v1/void/cancel/' . $this->shipmentIdentificationNumber . '?trackingnumber=' . $this->trackingNumber;
    }

    public function __construct(
        protected string $trackingNumber = '',
        protected string $shipmentIdentificationNumber = 'testing',
    ) {
    }
}
