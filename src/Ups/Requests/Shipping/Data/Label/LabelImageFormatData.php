<?php

namespace XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Shipping\Data\Label;

use Spatie\LaravelData\Data;

class LabelImageFormatData extends Data
{
    public function __construct(
        public string $SubVersion = '1801',
        public string $RequestOption = 'nonvalidate',
        public array $LabelImageFormat = [
            'Code' => 'GIF',
            'Description' => 'GIF',
        ],
    ) {
    }
}
