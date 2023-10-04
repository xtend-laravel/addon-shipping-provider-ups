<?php

namespace XtendLunar\Addons\ShippingProviderUps\ShippingModifiers;

use Lunar\Base\ShippingModifier;
use Lunar\DataTypes\Price;
use Lunar\DataTypes\ShippingOption;
use Lunar\Facades\ShippingManifest;
use Lunar\Models\Cart;
use Lunar\Models\TaxClass;
use XtendLunar\Addons\ShippingProviderUps\Concerns\InteractsWithUps;
use XtendLunar\Addons\ShippingProviderUps\Enums\Service;
use XtendLunar\Features\ShippingProviders\Models\ShippingProvider;

class UpsServices extends ShippingModifier
{
    use InteractsWithUps;

    public function handle(Cart $cart)
    {
        $taxClass = TaxClass::getDefault();

        if ($cart->shippingAddress()->doesntExist()) {
            return;
        }

        $upsServices = static::getServicesForCountry($cart->shippingAddress->country->iso2);

        $upsProvider = ShippingProvider::where('provider_key', 'ups')->sole();

        $rateResults = $this->getShippingRates($cart);

        $rates = [];
        foreach ($rateResults as $key => $value) {
            $rates[$value['Service']['Code']] = [
                'total' => $value['TotalCharges']['MonetaryValue'],
                'service' => Service::tryFrom($value['Service']['Code'])->description(),
            ];
        }

        // Sort array by lowest price
        asort($rates);

        // dump($rates, 'CA => NY');
        foreach ($rates as $serviceCode => $rate) {
            if (in_array($serviceCode, array_keys($upsServices))) {
                ShippingManifest::addOption(
                    new ShippingOption(
                        name: $rate['service'],
                        description: $rate['service'],
                        identifier: $serviceCode,
                        price: new Price(
                            (int)($rate['total'] * 100),
                            $cart->currency,
                        ),
                        taxClass: $taxClass,
                    )
                );
            }
        }

        $upsProvider->options()->where('is_enabled', 1)->get()->each(function ($option) use ($cart, $taxClass, $upsServices, $rates) {
            if (in_array($option->identifier, array_keys($upsServices)) && isset($rates[$option->identifier])) {
                ShippingManifest::addOption(
                    new ShippingOption(
                        name: $option->name,
                        description: $option->description,
                        identifier: $option->identifier,
                        price: new Price(
                            (int)($rates[$option->identifier] * 100),
                            $cart->currency,
                        ),
                        taxClass: $taxClass,
                    )
                );
            }
        });
    }
}
