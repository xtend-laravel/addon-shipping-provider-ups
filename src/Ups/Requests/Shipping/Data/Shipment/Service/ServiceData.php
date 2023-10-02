<?php

namespace XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Shipping\Data\Shipment\Service;

use Spatie\LaravelData\Data;

class ServiceData extends Data
{
    public function __construct(
        public string $Code,
        public ?string $Description,
    ) {
    }
}
