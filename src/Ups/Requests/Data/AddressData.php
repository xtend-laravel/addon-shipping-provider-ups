<?php

namespace XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Data;

use Spatie\LaravelData\Data;

class AddressData extends Data
{
    public function __construct(
        public array $AddressLine,
        public string $City,
        public ?string $StateProvinceCode,
        public ?string $PostalCode,
        public string $CountryCode,
    ) {
    }
}
