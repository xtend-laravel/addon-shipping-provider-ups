<?php

namespace XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Shipping\Data;

use Spatie\LaravelData\Data;
use XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Shipping\Data\Label\LabelSpecificationData;
use XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Shipping\Data\Shipment\ShipmentData;

class ShipmentRequestData extends Data
{
    public function __construct(
        public RequestData $Request,
        public ShipmentData $Shipment,
        public ?LabelSpecificationData $LabelSpecification,
    ) {
    }
}
