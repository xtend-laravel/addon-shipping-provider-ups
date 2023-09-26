<?php

namespace XtendLunar\Addons\ShippingProviderUps\ShippingModifiers;

use Lunar\Base\ShippingModifier;
use Lunar\DataTypes\Price;
use Lunar\DataTypes\ShippingOption;
use Lunar\Facades\ShippingManifest;
use Lunar\Models\Cart;
use Lunar\Models\TaxClass;
use XtendLunar\Addons\ShippingProviderUps\Concerns\InteractsWithUps;
use XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Rating\GetRate;
use XtendLunar\Addons\ShippingProviderUps\Ups\UpsApiConnector;
use XtendLunar\Features\ShippingProviders\Models\ShippingProvider;

class UpsServices extends ShippingModifier
{
    use InteractsWithUps;

    public function handle(Cart $cart)
    {
        $taxClass = TaxClass::getDefault();

        $upsServices = static::getServicesForCountry($cart->shippingAddress->country->iso2);

        $upsProvider = ShippingProvider::where('provider_key', 'ups')->sole();

        $upsProvider->options->each(function ($option) use ($cart, $taxClass, $upsServices) {
            if (in_array($option->identifier, array_keys($upsServices))) {
                ShippingManifest::addOption(
                    new ShippingOption(
                        name: $option->name,
                        description: $option->description,
                        identifier: $option->identifier,
                        price: new Price(
                            $this->calculateServicePrice($option, $cart),
                            $cart->currency,
                        ),
                        taxClass: $taxClass,
                    )
                );
            }
        });
    }

    protected function calculateServicePrice(ShippingOption $option, Cart $cart): int
    {
        return $this->getShippingRates($cart, $option->identifier);
        // @todo: Calculate the price of the shipping service from the API then move this logic into it's own class (Perhaps use Saloon v2?)
//        return 100;
    }
}
