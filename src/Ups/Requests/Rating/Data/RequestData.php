<?php

namespace XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Rating\Data;

use Spatie\LaravelData\Data;

class RequestData extends Data
{
    public function __construct(
        public array $TransactionReference = [
            'CustomerContext' => 'Customer Context',
        ],
    ) {
    }
}
