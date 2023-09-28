<?php

namespace XtendLunar\Addons\ShippingProviderUps\Concerns;

use Lunar\Models\State;
use Xtend\Extensions\Lunar\Core\Models\Cart;
use XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Rating\GetRate;
use XtendLunar\Addons\ShippingProviderUps\Ups\UpsApiConnector;

trait WithShippingRates
{
    public function getShippingRates(Cart $cart): array
    {
        $connector = new UpsApiConnector();
        $request = new GetRate('Shop');

        $payload = $this->makeRatePayload($cart);
        dump($payload);

        $request->body()->merge($payload);

        $response = $connector->send($request)->json();

        return $response['RateResponse']['RatedShipment'] ?? [];
    }

    public function getShippingRate(Cart $cart, string $serviceCode): array
    {
        $connector = new UpsApiConnector();
        $request = new GetRate('Rate');

        $payload = $this->makeRatePayload($cart, $serviceCode);

        $request->body()->merge($payload);

        $response = $connector->send($request)->json();

        return $response['RateResponse']['RatedShipment'] ?? [];
    }

    public function getCartWeight(Cart $cart)
    {
        return $cart->lines->sum(function ($line) {
            return $line->purchasable->weight_value * $line->quantity;
        });
    }

    public function getLineItemsQuantity(Cart $cart)
    {
        return $cart->lines->sum(function ($line) {
            return $line->quantity;
        });
    }

    public function getShipper(): array
    {
        return [
            'ShipperNumber' => config('ups.shipper.shipper_number'),
            'Address'       => [
                'AddressLine'       => config('ups.shipper.address.address_line'),
                'City'              => config('ups.shipper.address.city'),
                'StateProvinceCode' => config('ups.shipper.address.state_code'),
                'PostalCode'        => config('ups.shipper.address.postal_code'),
                'CountryCode'       => config('ups.shipper.address.country_code'),
            ]
        ];
    }

    public function getShipFrom(): array
    {
        return [
            'name'    => config('ups.ship_from.name'),
            'Address' => [
                'AddressLine'       => config('ups.ship_from.address.address_line'),
                'City'              => config('ups.ship_from.address.city'),
                'StateProvinceCode' => config('ups.ship_from.address.state_code'),
                'PostalCode'        => config('ups.ship_from.address.postal_code'),
                'CountryCode'       => config('ups.ship_from.address.country_code'),
            ]
        ];
    }

    public function makeRatePayload(Cart $cart, string $serviceCode = null): array
    {
        $shippingAddress = $cart->shippingAddress;
        $shipper = $this->getShipper();
        $shipFrom = $this->getShipFrom();

        $payload = [
            'RateRequest' => [
                'Request'  => [
                    'TransactionReference' => [
                        'CustomerContext' => 'CustomerContext'
                    ]
                ],
                'Shipment' => [
                    'Shipper'  => $shipper,
                    'ShipTo'   => [
                        'Name'    => collect([$shippingAddress->first_name, $shippingAddress->last_name])->filter()->implode(' '),
                        'Address' => [
                            'AddressLine'       => array_filter([$shippingAddress->line_one, $shippingAddress->line_two, $shippingAddress->line_three]),
                            'City'              => $shippingAddress->city,
                            'StateProvinceCode' => 'NY',
                            //'StateProvinceCode' => State::where('country_id', $shippingAddress->country_id)->where('name', $shippingAddress->state)->value('code'),
                            'PostalCode'        => '10003',
                            //'PostalCode'        => $shippingAddress->postcode,
                            'CountryCode'       => $shippingAddress->country->iso2,
                        ]
                    ],
                    'ShipFrom' => $shipFrom,
                    'Package'  => $this->definePackage($cart),
                ]
            ]
        ];

        if ($serviceCode) {
            $payload['RateRequest']['Shipment']['Service'] = [
                'Code' => $serviceCode
            ];
        }

        return $payload;
    }

    protected function definePackage(Cart $cart): array
    {
        $quantity = $this->getLineItemsQuantity($cart);
        $package = [
            'PackagingType' => [
                'Code' => config('ups.packaging.type')
            ],
            // @todo Note sure if dimensions really make a difference to the price?
            // 'Dimensions' => [
            //   'UnitOfMeasurement' => [
            //     'Code' => 'IN',
            //     'Description' => 'Inches'
            //   ],
            //   'Length' => '10',
            //   'Width' => '14',
            //   'Height' => '1',
            // ],
            'PackageWeight' => [
                'UnitOfMeasurement' => [
                    'Code' => config('ups.packaging.weight_unit')
                ],
                'Weight' => (string)$this->getCartWeight($cart),
            ]
        ];

        if ($quantity < 3) {
            $package['SimpleRate'] = ['Code' => 'S'];
        }

        return $package;
    }
}
