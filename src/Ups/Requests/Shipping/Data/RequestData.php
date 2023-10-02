<?php

namespace XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Shipping\Data;

use Spatie\LaravelData\Data;

class RequestData extends Data
{
    public function __construct(
        public string $SubVersion = '1801',
        public string $RequestOption = 'nonvalidate',
        public array $TransactionReference = [
            'CustomerContext' => 'Customer Context',
        ],
    ) {
    }
}
