<?php

namespace XtendLunar\Addons\ShippingProviderUps;

use Binaryk\LaravelRestify\Traits\InteractsWithRestifyRepositories;
use CodeLabX\XtendLaravel\Base\XtendAddonProvider;
use Illuminate\Support\Facades\Blade;
use Lunar\Base\ShippingModifiers;
use Xtend\Extensions\Lunar\Core\Concerns\XtendLunarCartPipeline;
use Xtend\Extensions\Lunar\Core\Models\Cart;
use XtendLunar\Addons\ShippingProviderUps\Commands\UpsSetupShippingOptions;
use XtendLunar\Addons\ShippingProviderUps\ShippingModifiers\UpsServices;

class ShippingProviderUpsProvider extends XtendAddonProvider
{
    use InteractsWithRestifyRepositories;
    use XtendLunarCartPipeline;

    public function register()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'xtend-lunar::shipping-provider-ups');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'xtend-lunar::shipping-provider-ups');
        $this->loadRestifyFrom(__DIR__.'/Restify', __NAMESPACE__.'\\Restify\\');
        $this->mergeConfigFrom(__DIR__.'/../config/ups.php', 'ups');
    }

    public function boot(ShippingModifiers $shippingModifier)
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                UpsSetupShippingOptions::class,
            ]);
        }

        Blade::componentNamespace('XtendLunar\\Addons\\ShippingProviderUps\\Components', 'xtend-lunar::shipping-provider-ups');

        $shippingModifier->add(
            modifier: UpsServices::class,
        );
    }
}
