<?php

namespace XtendLunar\Addons\ShippingProviderUps\Concerns;

use Illuminate\Support\Collection;
use XtendLunar\Features\ShippingProviders\Models\ShippingOption;

trait WithShippingOptions
{
    public static bool $hasAPE = true;

    public function getShippingOptions(): Collection
    {
        if (self::$hasAPE) {
            return collect($this->getShippingOption(70));
        }

        return ShippingOption::query()->where('is_enabled', '=', true)->get();
    }

    public function getShippingOption(int $serviceIdentifier): ShippingOption
    {
        return ShippingOption::query()->where('identifier', '=', $serviceIdentifier)->first();
    }
}
