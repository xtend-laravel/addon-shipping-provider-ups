<?php

namespace XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Rating\Data;

use Spatie\LaravelData\Data;
use XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Shipping\Data\RequestData;
use XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Shipping\Data\Shipment\ShipmentData;

class RateRequestData extends Data
{
    public function __construct(
        public RequestData $Request,
        public ?array $PickupType,
        public ShipmentData $Shipment,
    ) {
    }
}
