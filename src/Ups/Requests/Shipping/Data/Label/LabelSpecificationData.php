<?php

namespace XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Shipping\Data\Label;

use Spatie\LaravelData\Data;

class LabelSpecificationData extends Data
{
    public function __construct(
        public array $LabelImageFormat,
        public ?string $HTTPUserAgent,
        public array $LabelStockSize,
        public ?array $Instruction,
    ) {
    }

    public static function prepare()
    {
        return new static(
            LabelImageFormat: [
                'Code' => 'GIF',
                'Description' => 'GIF',
            ],
            HTTPUserAgent: 'Mozilla/4.5',
            LabelStockSize: [
                'Height' => '6',
                'Width' => '4',
            ],
            Instruction: null,
        );
    }
}
