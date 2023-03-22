<?php

namespace XtendLunar\Addons\ShippingProviderUps;

use Binaryk\LaravelRestify\Traits\InteractsWithRestifyRepositories;
use CodeLabX\XtendLaravel\Base\XtendAddonProvider;
use Illuminate\Support\Facades\Blade;
use Xtend\Extensions\Lunar\Core\Concerns\XtendLunarCartPipeline;

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

    public function boot()
    {
        Blade::componentNamespace('XtendLunar\\Addons\\ShippingProviderUps\\Components', 'xtend-lunar::shipping-provider-ups');
    }
}
