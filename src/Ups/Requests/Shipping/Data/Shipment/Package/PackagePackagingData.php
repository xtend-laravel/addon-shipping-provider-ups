<?php

namespace XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Shipping\Data\Shipment\Package;

use Spatie\LaravelData\Data;

class PackagePackagingData extends Data
{
    public function __construct(
        public string $Code,
        public ?string $Description,
    ) {
    }
}
