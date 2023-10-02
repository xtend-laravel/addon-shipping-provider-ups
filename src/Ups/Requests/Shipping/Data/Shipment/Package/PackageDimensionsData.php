<?php

namespace XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Shipping\Data\Shipment\Package;

use Spatie\LaravelData\Data;

class PackageDimensionsData extends Data
{
    public function __construct(
        public array $UnitOfMeasurement,
        public string $Length,
        public string $Width,
        public string $Height,
    ) {
    }
}
