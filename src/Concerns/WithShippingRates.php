<?php

namespace XtendLunar\Addons\ShippingProviderUps\Concerns;

use Lunar\Models\Cart;
use XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Rating\Data\RateRequestData;
use XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Rating\GetRate;
use XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Rating\Data\RequestData as RateRequest;
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

        $payload = [
            'RateRequest' => $this->makeRateRequestPayload($cart, null, true)->toArray(),
        ];

        $request->body()->merge($payload);

        $response = $connector->send($request)->json();

        return $response['RateResponse']['RatedShipment'] ?? [];
    }

    public function getShippingRate(Cart $cart, string $serviceCode): array
    {
        // $connector = new UpsApiConnector();
        // $request = new GetRate('Rate');
        //
        // $payload = $this->makeRateRequestPayload($cart, $serviceCode);
        //
        // $request->body()->merge($payload->toArray());
        //
        // $response = $connector->send($request)->json();
        //
        // return $response['RateResponse']['RatedShipment'] ?? [];
    }

    public function makeRateRequestPayload(Cart $cart, string $serviceCode = null, $fromShippingRateRequest = false): RateRequestData
    {
        return new RateRequestData(
            Request: new RateRequest(
                TransactionReference: [
                    'CustomerContext' => 'Customer Context',
                ],
            ),
            Shipment: ShipmentData::prepare($cart, $serviceCode, $fromShippingRateRequest),
        );
    }

    public function makeShippingRequestPayload(Cart $cart, string $serviceCode = null, $fromShippingRateRequest = false): ShipmentRequestData
    {
        return new ShipmentRequestData(
            Request: new RequestData(
                SubVersion: '1801',
                RequestOption: 'nonvalidate',
                TransactionReference: [
                    'CustomerContext' => 'Customer Context',
                ],
            ),
            Shipment: ShipmentData::prepare($cart, $serviceCode, $fromShippingRateRequest),
            LabelSpecification: LabelSpecificationData::prepare(),
        );
    }
}
