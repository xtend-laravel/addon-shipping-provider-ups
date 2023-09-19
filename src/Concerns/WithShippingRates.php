<?php

namespace XtendLunar\Addons\ShippingProviderUps\Concerns;

use Xtend\Extensions\Lunar\Core\Models\Address;
use XtendLunar\Addons\ShippingProviderUps\Ups\Requests\GetRate;
use XtendLunar\Addons\ShippingProviderUps\Ups\UpsApiConnector;

trait WithShippingRates
{
    public function getShippingRates(array $payload): array
    {
        $connector = new UpsApiConnector();

        $request = new GetRate('Shop');

        $request->body()->merge($payload);

        $response = $connector->send($request)->json();

        return $response['RateResponse']['RatedShipment'];
    }
}
