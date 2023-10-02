<?php

namespace XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Shipping\Data\Shipment\PaymentInformation;

use Spatie\LaravelData\Data;

class PaymentInformationData extends Data
{
    public function __construct(
        public ShipmentChargeData $ShipmentCharge,
    ) {
    }
}
