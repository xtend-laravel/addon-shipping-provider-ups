<section class="p-4 bg-white rounded-lg shadow" style="margin-top:56px;">
  <header class="flex items-center justify-between">
    <strong class="text-gray-700">
    UPS Shipment
    </strong>
    <svg class="w-10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
      <path fill="currentColor" d="M103.2 303c-5.2 3.6-32.6 13.1-32.6-19V180H37.9v102.6c0 74.9 80.2 51.1 97.9 39V180h-32.6zM4 74.82v220.9c0 103.7 74.9 135.2 187.7 184.1c112.4-48.9 187.7-80.2 187.7-184.1V74.82c-116.3-61.6-281.8-49.6-375.4 0zm358.1 220.9c0 86.6-53.2 113.6-170.4 165.3c-117.5-51.8-170.5-78.7-170.5-165.3v-126.4c102.3-93.8 231.6-100 340.9-89.8zm-209.6-107.4v212.8h32.7v-68.7c24.4 7.3 71.7-2.6 71.7-78.5c0-97.4-80.7-80.92-104.4-65.6zm32.7 117.3v-100.3c8.4-4.2 38.4-12.7 38.4 49.3c0 67.9-36.4 51.8-38.4 51zm79.1-86.4c.1 47.3 51.6 42.5 52.2 70.4c.6 23.5-30.4 23-50.8 4.9v30.1c36.2 21.5 81.9 8.1 83.2-33.5c1.7-51.5-54.1-46.6-53.4-73.2c.6-20.3 30.6-20.5 48.5-2.2v-28.4c-28.5-22-79.9-9.2-79.7 31.9z"/>
    </svg>
  </header>
  <div>
    <div class="grid grid-cols-2 text-sm gap-2 px-3 py-3 border-b">
      <dt class="font-medium text-gray-500">Service</dt>
      <dd class="text-right">{{ $this->serviceDescription }}</dd>
    </div>
  </div>
  @if ($this->trackingNumber)
    <div>
      <div class="grid grid-cols-2 text-sm gap-2 px-3 py-3 border-b">
        <dt class="font-medium text-gray-500">Tracking Number</dt>
        <dd class="text-right">{{ $this->trackingNumber }}</dd>
      </div>
    </div>
  @endif
  <!-- Form label download -->
  <div class="md:flex justify-end gap-2 mt-4">
    @if ($this->trackingNumber)
      <button wire:click="voidShipment" class="inline-block rounded-lg bg-red-600 px-4 py-3 text-center text-sm font-semibold text-white outline-none ring-indigo-300 transition duration-100 hover:bg-gray-800 focus-visible:ring active:bg-red-600 md:text-sm">
        Void Shipment
      </button>
      <button wire:click="printLabel" class="inline-block rounded-lg bg-green-600 px-4 py-3 text-center text-sm font-semibold text-white outline-none ring-indigo-300 transition duration-100 hover:bg-gray-800 focus-visible:ring active:bg-green-600 md:text-sm">
        Print Label
      </button>
    @else
      <button wire:click="createShipment" class="inline-block rounded-lg bg-blue-600 px-4 py-3 text-center text-sm font-semibold text-white outline-none ring-indigo-300 transition duration-100 hover:bg-gray-800 focus-visible:ring active:bg-blue-600 md:text-sm">
        Create Label
      </button>
    @endif
  </div>

  <x-hub::modal wire:model="showShippingWarning">
    <div class="flex flex-col overflow-hidden rounded-lg bg-gray-900 sm:flex-row">
      <!-- content - start -->
      <div class="flex w-full flex-col p-4 sm:p-8">
        <h2 class="text-xl font-bold text-white md:text-2xl lg:text-4xl uppercase">UPS Shipping Label</h2>
        <p class="mb-8 max-w-md text-gray-400">This label has now been voided. Please create a new label.</p>

        <div class="mt-auto">
          <button wire:click="voidShipment" class="inline-block rounded-lg bg-red-600 px-8 py-3 text-center text-sm font-semibold text-white outline-none ring-indigo-300 transition duration-100 hover:bg-gray-800 focus-visible:ring active:bg-red-600 md:text-base">
            Void Shipment
          </button>
        </div>
      </div>
      <!-- content - end -->
    </div>
  </x-hub::modal>

  <x-hub::modal max-width="6xl" wire:model="showShippingSuccess">
    <div class="flex flex-col overflow-hidden rounded-lg bg-gray-900 sm:flex-row">
      <!-- content - start -->
      <div class="flex w-full flex-col p-4 sm:w-1/2 sm:p-8 lg:w-2/5">
        <h2 class="text-xl font-bold text-white md:text-2xl lg:text-4xl uppercase">UPS Shipping Label</h2>
        <p class="mb-4 text-xl font-semibold text-white md:text-2xl lg:text-4xl">Express Delivery</p>

        <p class="mb-8 max-w-md text-gray-400">Please print your shipping label below and attach it to your package.</p>

      </div>
      <!-- content - end -->

      <!-- image - start -->
      <div class="order-first h-48 w-full bg-gray-700 sm:order-none sm:h-auto sm:w-1/2 lg:w-3/5 p-4">
        <img src="{{ $this->labelImageUrl }}" loading="lazy" alt="Photo by Dom Hill" class="h-full w-full object-cover object-center" />
      </div>
      <!-- image - end -->
    </div>
  </x-hub::modal>

</section>
