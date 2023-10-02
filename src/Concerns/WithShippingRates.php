<?php

namespace XtendLunar\Addons\ShippingProviderUps\Concerns;

use Lunar\Models\Cart;
use XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Rating\GetRate;
use XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Shipping\Data\Label\LabelSpecificationData;
use XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Shipping\Data\RequestData;
use XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Shipping\Data\Shipment\ShipmentData;
use XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Shipping\Data\ShipmentRequestData;
use XtendLunar\Addons\ShippingProviderUps\Ups\UpsApiConnector;

trait WithShippingRates
{
    public function getShippingRates(Cart $cart): array
    {
        $connector = new UpsApiConnector();
        $request = new GetRate('Shop');

        $payload = $this->makeRatePayload($cart);
        $request->body()->merge($payload->toArray());

        $response = $connector->send($request)->json();

        return $response['RateResponse']['RatedShipment'] ?? [];
    }

    public function getShippingRate(Cart $cart, string $serviceCode): array
    {
        $connector = new UpsApiConnector();
        $request = new GetRate('Rate');

        $payload = $this->makeRatePayload($cart, $serviceCode);

        $request->body()->merge($payload->toArray());

        $response = $connector->send($request)->json();

        return $response['RateResponse']['RatedShipment'] ?? [];
    }

    public function makeRatePayload(Cart $cart, string $serviceCode = null): ShipmentRequestData
    {
        return new ShipmentRequestData(
            Request: new RequestData(
                SubVersion: '1801',
                RequestOption: 'nonvalidate',
                TransactionReference: [
                    'CustomerContext' => 'Customer Context',
                ],
            ),
            Shipment: ShipmentData::prepare($cart, $serviceCode),
            LabelSpecification: LabelSpecificationData::prepare(),
        );
    }
}
