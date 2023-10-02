<?php

namespace XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Data;

use Spatie\LaravelData\Data;

class ContactData extends Data
{
    public function __construct(
        public string $Name,
        public ?string $AttentionName,
        public ?string $TaxIdentificationNumber,
        public ?array $Phone,
        public ?string $ShipperNumber,
        public ?string $FaxNumber,
        public ?string $EMailAddress,
        public AddressData $Address,
    ) {
    }
}
