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
      <dd class="text-right">UPS Ground</dd>
    </div>
  </div>
  <!-- Form label download -->
  <div class="md:flex justify-between gap-2">
    <button wire:click="createShipment" class="mt-4 p-2 items-center text-xs font-semibold bg-blue-600 text-white rounded-lg shadow">
      Create Shipment Label
    </button>
    <button wire:click="printLabel" class="inline-flex underline mt-4 items-center text-blue-600 text-xs px-4 py-2 font-semibold">
      Print Label
    </button>
  </div>
</section>
