<?php

namespace XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Shipping\Data\Shipment;

use Lunar\Models\Cart;
use Lunar\Models\CartAddress;
use Lunar\Models\State;
use Spatie\LaravelData\Data;
use XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Data\AddressData;
use XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Data\ContactData;
use XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Shipping\Data\Shipment\Package\PackageData;
use XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Shipping\Data\Shipment\Service\ServiceData;

class ShipmentData extends Data
{
    public function __construct(
        public string $Description,
        public ContactData $Shipper,
        public ContactData $ShipTo,
        public ?ContactData $ShipFrom,
        public ?array $PaymentDetails,
        public ?ServiceData $Service,
        public ?PackageData $Package,
        public ?array $PaymentInformation = null,
    ) {
    }

    public static function prepare(Cart $cart, string $serviceCode = null, bool $fromShippingRateRequest = false): static
    {
        $description = 'Order #' . $cart->id;
        $address = $cart->shippingAddress;
        return new static(
            $description,
            static::getShipper(),
            static::getShipTo($address),
            static::getShipFrom(),
            $fromShippingRateRequest ? static::getPaymentInformation() : null,
            static::getService($cart, $serviceCode),
            static::getPackage($cart, $fromShippingRateRequest),
            !$fromShippingRateRequest ? static::getPaymentInformation() : null
        );
    }

    public static function getShipper(): ContactData
    {
        return ContactData::from([
            'ShipperNumber' => config('ups.shipper.shipper_number'),
            'Name' => config('ups.shipper.shipper_name'),
            //'AttentionName' => 'AttentionName',
            'CompanyDisplayableName' => config('ups.shipper.shipper_number'),
            'Phone' => [
                'Number' => config('ups.shipper.shipper_phone'),
            ],
            'Address' => AddressData::from([
                'AddressLine' => config('ups.shipper.address.address_line'),
                'City' => config('ups.shipper.address.city'),
                'StateProvinceCode' => config('ups.shipper.address.state_code'),
                'PostalCode' => config('ups.shipper.address.postal_code'),
                'CountryCode' => config('ups.shipper.address.country_code'),
            ]),
        ]);
    }

    public static function getShipTo(CartAddress $shippingAddress): ContactData
    {
        return ContactData::from([
            'Name' => collect([$shippingAddress->first_name, $shippingAddress->last_name])->filter()->implode(' '),
            //'AttentionName' => 'AttentionName',
            'CompanyDisplayableName' => 'CompanyDisplayableName',
            'Phone' => [
                'Number' => $shippingAddress->phone ?? '---',
            ],
            'Address' => AddressData::from([
                'AddressLine' => array_filter([$shippingAddress->line_one, $shippingAddress->line_two, $shippingAddress->line_three]),
                'City' => $shippingAddress->city,
                'StateProvinceCode' => State::where('country_id', $shippingAddress->country_id)->where('name', $shippingAddress->state)->value('code'),
                'PostalCode' => $shippingAddress->postcode,
                'CountryCode' => $shippingAddress->country->iso2,
            ]),
        ]);
    }

    public static function getShipFrom(): ContactData
    {
        return ContactData::from([
            'Name' => config('ups.ship_from.name'),
            'AttentionName' => config('ups.ship_from.name'),
            'CompanyDisplayableName' => config('ups.ship_from.name'),
            'Phone' => [
                'Number' => '1234567890',
            ],
            'Address' => AddressData::from([
                'AddressLine' => config('ups.ship_from.address.address_line'),
                'City' => config('ups.ship_from.address.city'),
                'StateProvinceCode' => config('ups.ship_from.address.state_code'),
                'PostalCode' => config('ups.ship_from.address.postal_code'),
                'CountryCode' => config('ups.ship_from.address.country_code'),
            ]),
        ]);
    }

    public static function getPaymentInformation(): array
    {
        return [
            'ShipmentCharge' => [
                'Type' => '01',
                'BillShipper' => [
                    'AccountNumber' => config('ups.shipper.shipper_number'),
                ],
            ],
        ];
    }

    public static function getService(Cart $cart, $serviceCode = null): ?ServiceData
    {
        return $serviceCode ? ServiceData::from([
            'Code' => $serviceCode,
            'Description' => 'Service Description',
        ]) : null;
    }

    public static function getPackage(Cart $cart, bool $fromShippingRateRequest): PackageData
    {
        $quantity = static::getLineItemsQuantity($cart);
        $package = [
            'PackagingType' => [
                'Code' => config('ups.packaging.type')
            ],
            'Packaging' => [
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
                'Weight' => (string)static::getCartWeight($cart),
            ]
        ];

        if ($quantity < 3) {
            $package['SimpleRate'] = ['Code' => 'S'];
        }

        return PackageData::from([
            'PackagingType' => [
                'Code' => '02',
                'Description' => 'Packaging',
            ],
            'Packaging' => [
                'Code' => config('ups.packaging.type'),
                'Description' => 'Rate',
            ],
            'PackageWeight' => [
                'UnitOfMeasurement' => [
                    'Code' => config('ups.packaging.weight_unit'),
                    'Description' => 'Pounds',
                ],
                'Weight' => '1',
            ],
            'Dimensions' => [
                'UnitOfMeasurement' => [
                    'Code' => config('ups.packaging.unit_of_measurement'),
                    'Description' => 'Inches',
                ],
                'Length' => '1',
                'Width' => '1',
                'Height' => '1',
            ],
        ]);
    }

    public static function getLineItemsQuantity(Cart $cart): int
    {
        return $cart->lines->sum(function ($line) {
            return $line->quantity;
        });
    }

    public static function getCartWeight(Cart $cart): int
    {
        return $cart->lines->sum(function ($line) {
            return $line->purchasable->weight_value * $line->quantity;
        });
    }
}
