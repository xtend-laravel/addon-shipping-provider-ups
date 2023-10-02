<?php

namespace XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Shipping\Data\Shipment\PaymentInformation;

use Spatie\LaravelData\Data;

class ShipmentChargeData extends Data
{
    public function __construct(
        public string $Type,
        public ?array $BillShipper,
    ) {
    }
}
