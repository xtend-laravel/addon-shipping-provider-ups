<?php

namespace XtendLunar\Addons\ShippingProviderUps\Concerns;

trait WithShippingRates
{
    public function getShippingRates(): array
    {
        // @todo: Implement this method.

        return [
            'rate1' => 'value1',
            'rate2' => 'value2',
            'rate3' => 'value3',
        ];
    }
}
