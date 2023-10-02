<?php

namespace XtendLunar\Addons\ShippingProviderUps\Concerns;

use Xtend\Extensions\Lunar\Core\Models\Cart;
use XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Rating\GetRate;
use XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Shipping\LabelRecovery;
use XtendLunar\Addons\ShippingProviderUps\Ups\UpsApiConnector;

trait WithShippingLabels
{
    public function getShippingLabel(): array
    {
        $connector = new UpsApiConnector();
        $request = new LabelRecovery();

        $payload = $this->makeLabelPayload();
        dump($payload);

        $request->body()->merge($payload);

        $response = $connector->send($request)->json();

        return $response['RateResponse']['RatedShipment'] ?? [];
    }

    public function makeLabelPayload(Cart $cart): array
    {
        $payload = [
            'LabelRecoveryRequest' => [
                'LabelDelivery' => [
                    'LabelLinkIndicator' => '',
                    'ResendEmailIndicator' => '',
                ],
                'LabelSpecification' => [
                    'HTTPUserAgent' => 'Mozilla/4.5',
                    'LabelImageFormat' => [
                        'Code' => 'ZPL',
                    ],
                    'LabelStockSize' => [
                        'Height' => '6',
                        'Width' => '4',
                    ],
                ],
                'Request' => [
                    'RequestOption' => 'Non_Validate',
                    'SubVersion' => '1903',
                    'TransactionReference' => [
                        'CustomerContext' => '',
                    ],
                ],
                'TrackingNumber' => '1Z12345E8791315509',
                'Translate' => [
                    'Code' => '01',
                    'DialectCode' => 'US',
                    'LanguageCode' => 'eng',
                ],
            ],
        ];

        return $payload;
    }
}
