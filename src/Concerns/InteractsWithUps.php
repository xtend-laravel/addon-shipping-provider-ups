<?php

namespace XtendLunar\Addons\ShippingProviderUps\Concerns;

use Ptondereau\LaravelUpsApi\Facades\UpsRate;
use Ups\Entity\Address;
use Ups\Entity\Dimensions;
use Ups\Entity\Package;
use Ups\Entity\PackagingType;
use Ups\Entity\ShipFrom;
use Ups\Entity\Shipment;
use Ups\Entity\UnitOfMeasurement;
use Ups\Rate;
use Xtend\Extensions\Lunar\Core\Models\Cart;

trait InteractsWithUps
{
    use WithShippingOptions, WithShippingRates, WithShippingLabels, WithServicesOrigin;

    public function boot()
    {
        /** @var Rate $rate */
        $rate = app('ups.rate');

        $rate = $rate->getRate($this->getShipment());
        dd($rate);
    }

    protected function getShipment(): Shipment
    {
        $cart = Cart::query()->first();
        $shipment = new Shipment();

        $shipperAddress = $shipment->getShipper()->getAddress();
        $shipperAddress->setPostalCode($cart->shippingAddress->postcode);

        $address = new Address();
        $address->setPostalCode('99205');
        $shipFrom = new ShipFrom();
        $shipFrom->setAddress($address);

        $shipment->setShipFrom($shipFrom);

        $shipTo = $shipment->getShipTo();
        $shipTo->setCompanyName('Test Ship To');
        $shipToAddress = $shipTo->getAddress();
        $shipToAddress->setPostalCode('99205');

        $package = new Package();
        $package->getPackagingType()->setCode(PackagingType::PT_PACKAGE);
        $package->getPackageWeight()->setWeight(10);

        // if you need this (depends of the shipper country)
        $weightUnit = new UnitOfMeasurement;
        $weightUnit->setCode(UnitOfMeasurement::UOM_LBS);
        $package->getPackageWeight()->setUnitOfMeasurement($weightUnit);

        $dimensions = new Dimensions();
        $dimensions->setHeight(10);
        $dimensions->setWidth(10);
        $dimensions->setLength(10);

        $unit = new UnitOfMeasurement;
        $unit->setCode(UnitOfMeasurement::UOM_IN);

        $dimensions->setUnitOfMeasurement($unit);
        $package->setDimensions($dimensions);

        $shipment->addPackage($package);
        return $shipment;
    }
}
