<?php

namespace XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Shipping\Data\Shipment\Package;

use Spatie\LaravelData\Data;

class PackageData extends Data
{
    public function __construct(
        public ?string $Description,
        public PackagePackagingData $Packaging,
        public PackageDimensionsData $Dimensions,
        public PackageWeightData $PackageWeight,
    ) {
    }
}
