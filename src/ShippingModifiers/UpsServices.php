<?php

namespace XtendLunar\Addons\ShippingProviderUps\ShippingModifiers;

use Binaryk\LaravelRestify\Http\Controllers\PerformRepositoryActionController;
use Lunar\Base\ShippingModifier;
use Lunar\DataTypes\Price;
use Lunar\DataTypes\ShippingOption;
use Lunar\Facades\ShippingManifest;
use Lunar\Models\Cart;
use Lunar\Models\CartLine;
use Lunar\Models\TaxClass;
use XtendLunar\Addons\ShippingProviderUps\Concerns\InteractsWithUps;
use XtendLunar\Addons\ShippingProviderUps\Enums\Service;
use XtendLunar\Features\ShippingProviders\Models\ShippingProvider;

class UpsServices extends ShippingModifier
{
    use InteractsWithUps;

    public function handle(Cart $cart)
    {
        $taxClass = TaxClass::where('name', 'UPS')->sole();

        if ($cart->shippingAddress()->doesntExist() || !$cart->shippingAddress->country) {
            return;
        }

        $preorderItems = $cart->lines->filter(
            fn (CartLine $cartLine) => $cartLine->purchasable->attribute_data && $cartLine->purchasable->attribute_data['availability']->getValue() === 'pre-order',
        )->isNotEmpty();

        if ($preorderItems) {
            $this->addPreorderShippingOption($cart, $taxClass);
            return;
        }

        $upsServices = static::getServicesForCountry($cart->shippingAddress->country->iso2);

        $upsProvider = ShippingProvider::where('provider_key', 'ups')->sole();

        $rateResults = $this->getShippingRates($cart);

        $rates = [];
        foreach ($rateResults as $key => $value) {
            $rates[$value['Service']['Code']] = [
                'total' => $value['Service']['Code'] !== '03' ? $value['TotalCharges']['MonetaryValue'] : 0,
                'service' => Service::tryFrom($value['Service']['Code'])->description(),
                'delay_days' => $value['GuaranteedDelivery']['BusinessDaysInTransit'] ?? null,
                'delay_description' => $value['GuaranteedDelivery']['BusinessDaysInTransit']['DeliveryByTime'] ?? null,
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
                        description: $rate['delay_days'] ? __('Delivery within :d days:time', [
                            'd' => $rate['delay_days'],
                            'time' => $rate['delay_description'] ? ' (' . $rate['delay_description'] . ')' : '',
                        ]) : $rate['service'],
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
                $shippingRate = $rates[$option->identifier]['total'] ?? 0;
                ShippingManifest::addOption(
                    new ShippingOption(
                        name: $option->name,
                        description: $option->description,
                        identifier: $option->identifier,
                        price: new Price(
                            (int)($shippingRate * 100),
                            $cart->currency,
                        ),
                        taxClass: new TaxClass,
                    )
                );
            }
        });
    }

    protected function addPreorderShippingOption(Cart $cart, TaxClass $taxClass)
    {
        ShippingManifest::addOption(
            new ShippingOption(
                name: 'Pre-order shipping',
                description: 'One or more items in your cart are pre-order items. Your order will be shipped once all items are available.',
                identifier: 'AWR-PREORDER-SHIPPING',
                price: new Price(2000, $cart->currency, 1),
                taxClass: $taxClass
            )
        );
    }
}
