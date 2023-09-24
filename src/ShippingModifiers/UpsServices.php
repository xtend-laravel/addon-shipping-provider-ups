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

        $upsProvider = ShippingProvider::where('provider_key', 'ups')->sole();

        $connector = new UpsApiConnector();
        $request = new GetRate();
        $response = $connector->send($request);

        dd($response->json());

        $upsProvider->options->each(function ($option) use ($cart, $taxClass) {
            ShippingManifest::addOption(
                new ShippingOption(
                    name: $option->name,
                    description: $option->description,
                    identifier: $option->identifier,
                    price: new Price($this->calculateServicePrice(), $cart->currency, 1),
                    taxClass: $taxClass,
                )
            );
        });
    }

    protected function calculateServicePrice(): int
    {
        $connector = new UpsApiConnector();

        // @todo: Calculate the price of the shipping service from the API then move this logic into it's own class (Perhaps use Saloon v2?)
        return 100;
    }
}
