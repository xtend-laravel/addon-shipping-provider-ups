<?php

namespace XtendLunar\Addons\ShippingProviderUps\Slots;

use Livewire\Component;
use Lunar\Hub\Slots\AbstractSlot;
use Lunar\Hub\Slots\Traits\HubSlot;
use XtendLunar\Addons\ShippingProviderUps\Concerns\InteractsWithUps;
use XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Shipping\Shipment;
use XtendLunar\Addons\ShippingProviderUps\Ups\UpsApiConnector;

class ShipmentLabelSlot extends Component implements AbstractSlot
{
    use HubSlot;
    use InteractsWithUps;

    public static function getName()
    {
        return 'hub.orders.slots.shipment-label-slot';
    }

    public function getSlotHandle()
    {
        return 'shipment-label-slot';
    }

    public function getSlotInitialValue()
    {
        return [];
    }

    public function getSlotPosition()
    {
        return 'top';
    }

    public function getSlotTitle()
    {
        return 'Shipping';
    }

    public function createShipment()
    {
        $connector = new UpsApiConnector();
        $request = new Shipment();

        $payload = $this->prepareShipmentPayload();

        $request->body()->merge($payload);

        $response = $connector->send($request)->json();
        dd($response, $payload);

        $this->emit('createShipment');
    }

    protected function prepareShipmentPayload(): array
    {
        /** @var \Lunar\Models\Order $this */
        $cart = $this->slotModel->cart;
        $ratePayload = $this->makeRatePayload($cart, '03');

        return [
            'ShipmentRequest' => $ratePayload->toArray(),
        ];
    }

    public function printLabel()
    {
        $this->emit('printLabel');
    }

    public function render()
    {
        return view('shipping-provider-ups::slots.ups-shipping-label-slot');
    }
}
