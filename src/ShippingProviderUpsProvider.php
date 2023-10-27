<?php

namespace XtendLunar\Addons\ShippingProviderUps;

use Binaryk\LaravelRestify\Traits\InteractsWithRestifyRepositories;
use CodeLabX\XtendLaravel\Base\XtendAddonProvider;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use Lunar\Base\ShippingModifiers;
use Lunar\Hub\Facades\Slot;
use Lunar\Models\TaxClass;
use Xtend\Extensions\Lunar\Core\Concerns\XtendLunarCartPipeline;
use XtendLunar\Addons\ShippingProviderUps\Commands\UpsSetupShippingOptions;
use XtendLunar\Addons\ShippingProviderUps\ShippingModifiers\UpsServices;
use XtendLunar\Addons\ShippingProviderUps\Slots\ShipmentLabelSlot;
use XtendLunar\Addons\ShippingProviderUps\Ups\Requests\GetAccessToken;
use XtendLunar\Features\ShippingProviders\Models\ShippingProvider;

class ShippingProviderUpsProvider extends XtendAddonProvider
{
    use InteractsWithRestifyRepositories;
    use XtendLunarCartPipeline;

    public function register()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'shipping-provider-ups');

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'xtend-lunar::shipping-provider-ups');
        $this->loadRestifyFrom(__DIR__.'/Restify', __NAMESPACE__.'\\Restify\\');
        $this->mergeConfigFrom(__DIR__.'/../config/ups.php', 'ups');

        $this->app->bind(GetAccessToken::class, function () {
            return new GetAccessToken(
                clientId: config('ups.client_id'),
                clientSecret: config('ups.client_secret')
            );
        });
    }

    public function boot(ShippingModifiers $shippingModifier)
    {
        $this->publishes([
           __DIR__.'/../config/ups.php' => config_path('ups.php'),
        ]);

        Livewire::component('hub.orders.slots.shipment-label-slot', ShipmentLabelSlot::class);

        Slot::register('order.show', ShipmentLabelSlot::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                UpsSetupShippingOptions::class,
            ]);
        }

        Blade::componentNamespace('XtendLunar\\Addons\\ShippingProviderUps\\Components', 'xtend-lunar::shipping-provider-ups');

        if (!ShippingProvider::query()->where('provider_key', 'ups')->exists()) {
            ShippingProvider::query()->create([
                'name' => 'UPS',
                'provider_key' => 'ups',
            ]);
        }

        if (!TaxClass::query()->where('name', 'UPS')->exists()) {
            TaxClass::query()->create([
                'name' => 'UPS',
            ]);
        }

        $shippingModifier->add(
            modifier: UpsServices::class,
        );
    }
}
