<?php

namespace XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Shipping\Data\Shipment\Package;

use Spatie\LaravelData\Data;

class PackageWeightData extends Data
{
    public function __construct(
        public array $UnitOfMeasurement,
        public string $Weight,
    ) {
    }
}
