<?php

namespace XtendLunar\Addons\ShippingProviderUps\Slots;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Intervention\Image;
use Livewire\Component;
use Lunar\Hub\Http\Livewire\Traits\Notifies;
use Lunar\Hub\Slots\AbstractSlot;
use Lunar\Hub\Slots\Traits\HubSlot;
use Lunar\Models\Order;
use XtendLunar\Addons\ShippingProviderUps\Concerns\InteractsWithUps;
use XtendLunar\Addons\ShippingProviderUps\Enums\Service;
use XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Shipping\Shipment;
use XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Shipping\VoidShipment;
use XtendLunar\Addons\ShippingProviderUps\Ups\UpsApiConnector;

class ShipmentLabelSlot extends Component implements AbstractSlot
{
    use HubSlot;
    use InteractsWithUps;
    use Notifies;

    public bool $showShippingWarning = false;

    public bool $showShippingSuccess = false;

    public ?string $labelImageUrl = '';

    public ?string $serviceDescription = '';

    public ?string $shipmentIdentificationNumber = '';

    public ?string $trackingNumber = '';

    public Order|string $order;

    public function mount()
    {
        $this->order = $this->slotModel;

        if ($this->order instanceof Order) {
            $this->shipmentIdentificationNumber = $this->order->meta?->shipment_identification_number ?? null;
            $this->trackingNumber = $this->order->meta?->tracking_number ?? null;

            if ($this->shipmentIdentificationNumber && $this->trackingNumber) {
                $this->labelImageUrl = Storage::url('shipping-labels/' . $this->trackingNumber . '.gif');
            }

            $shippingOptionCode = $this->order->cart?->shippingAddress?->shipping_option ?? null;
            $this->serviceDescription = Service::tryFrom($shippingOptionCode)?->description() ?? 'FREE SHIPPING';
        }
    }

    public function closeModal()
    {
        $this->showShippingWarning = false;
        $this->showShippingSuccess = false;
    }

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

        // $connector->debugRequest(
        //     function (PendingRequest $pendingRequest, RequestInterface $psrRequest) {
        //         dd($pendingRequest->headers()->all());
        //     }
        // );

        $response = $connector->send($request)->json();

        if ($this->shipmentAlreadyExists()) {
            $this->showShippingWarning = true;
            return;
        }

        if ($packageResults = $response['ShipmentResponse']['ShipmentResults']['PackageResults'] ?? false) {
            $this->prepareLabel($packageResults);

            $this->order->update([
                'meta' => collect($this->order->meta ?? [])->merge([
                    'tracking_number' => $packageResults['TrackingNumber'],
                    'shipment_identification_number' => $response['ShipmentResponse']['ShipmentResults']['ShipmentIdentificationNumber'],
                ]),
            ]);

            $this->showShippingSuccess = true;
        }
    }

    public function voidShipment()
    {
        $connector = new UpsApiConnector();
        $request = new VoidShipment(
            trackingNumber: $this->trackingNumber,
            shipmentIdentificationNumber: $this->shipmentIdentificationNumber,
        );

        $this->order->update([
            'meta' => collect($this->order->meta ?? [])->forget(['tracking_number', 'shipment_identification_number']),
        ]);

        $response = $connector->send($request)->json();

        if ($response['VoidShipmentResponse']['Response']['ResponseStatus']['Code'] === '1') {
            $this->showShippingSuccess = false;
            $this->notify(
                message: __('Shipment with :trackingNumber has been successfully voided.', [
                    'trackingNumber' => $this->trackingNumber,
                ]),
                route: 'hub.orders.show',
                routeParams: ['order' => $this->order],
            );
        }
    }

    protected function shipmentAlreadyExists(): bool
    {
        return collect($this->order->meta)->keys()->contains('tracking_number');
    }

    protected function prepareShipmentPayload(): array
    {
        $cart = $this->order->cart;
        $shippingOptionCode = $cart->shippingAddress?->shipping_option ?? Service::UPS_GROUND;
        $ratePayload = $this->makeShippingRequestPayload($cart, $shippingOptionCode);

        return [
            'ShipmentRequest' => $ratePayload->toArray(),
        ];
    }

    public function prepareLabel(array $packageResults): void
    {
        $imageData = base64_decode($packageResults['ShippingLabel']['GraphicImage']);

        $path = 'shipping-labels/' . $packageResults['TrackingNumber'];
        Storage::put($path.'.gif', $imageData);

        $this->labelImageUrl = Storage::url($path.'.gif');

        //$labelView = $this->labelView($packageResults);
        //Pdf::loadView($labelView->getName(), $labelView->getData())->save(storage_path('app/'.$path.'.pdf'))->stream();
    }

    public function printLabel()
    {
        $path = 'shipping-labels/' . $this->trackingNumber;

        $label = Storage::url($path.'.gif');
        $image = Image\Facades\Image::make($label);
        $image->rotate(-90);

        Storage::put($path.'-print.gif', $image->stream()->__toString());

        return Storage::download($path.'-print.gif');

        // return Pdf::loadView('adminhub::pdf.order', [
        //     'order' => $this->order,
        // ])->stream("Order-{$this->order->reference}.pdf");
    }

    protected function labelView(array $packageResults): View
    {
        return view('shipping-provider-ups::pdf.shipping-label', [
            'labelImagePath' => storage_path('app/shipping-labels/' . $packageResults['TrackingNumber'] . '.gif'),
            'packageResults' => $packageResults,
        ]);
    }

    public function render()
    {
        return view('shipping-provider-ups::slots.ups-shipping-label-slot');
    }
}
