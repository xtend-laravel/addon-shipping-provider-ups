<?php

namespace XtendLunar\Addons\ShippingProviderUps\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use XtendLunar\Features\ShippingProviders\Models\ShippingOption;
use XtendLunar\Features\ShippingProviders\Models\ShippingProvider;

class UpsSetupShippingOptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ups:setup-shipping-options';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import shipping options from UPS';

    public function handle(): int
    {
        $this->components->info('Importing shipping options...');

        foreach ($this->getPrepareData() as $data) {
            ShippingOption::updateOrCreate(
                [
                    'provider_id' => ShippingProvider::where('provider_key', 'ups')->first()->id,
                    'identifier' => $data['identifier'],
                ],
                $data
            );
        }

        $this->components->info('Done importing shipping options.');

        return self::SUCCESS;
    }

    private function getShippingOptions(): array
    {
        // @todo This is based off the old API we now need to define an ENUM class for all the service codes based on:
        // https://github.com/gabrielbull/php-ups-api/blob/cc5438412982425ef801ea61ba48060ce617d9b3/src/Entity/Service.php#L82
        return [
            '11' => [
                'code'                 => '11',
                'name'                 => 'UPS Standard ®',
                'originCountries'      => ['BE', 'NL', 'LU', 'FR', 'ES', 'MA'],
                'destinationCountries' => [
                    'BE', 'NL', 'LU', 'FR', 'ES', 'UK', 'DE', 'PL', 'IT', 'MA', 'AT', 'DK',
                    'MC', 'CZ', 'HR', 'HU', 'SI', 'SK', 'IE', 'PT', 'GR', 'SE', 'BG', 'EE', 'LT', 'LV', 'RO', 'AD',
                    'CH', 'GG', 'LI', 'JE', 'NO', 'SM',
                ],
                'onlySameAsOrigin'     => false,
                'wsCode'               => 'ST',
                'trackingURL'          => 'http://wwwapps.ups.com/etracking/tracking.cgi?tracknum=@',
                'delays'               => [
                    'fr' => 'Livraison en 1 à 3+ jours avant la fin de journée le même pays, 1 à 5+ jours avant la fin de journée pour le reste du monde.',
                    'en' => 'Delivery in 1 to 3+ days by end of day in the same country, 1 to 5+ days by end of day in the rest of the world.',
                    'es' => 'Entrega en 1 a 3+ días al final del día en el mismo país, de 1 a 5 + días de final del día en el resto del mundo.',
                    'nl' => 'Levering in 1-3+ dagen vóór dag einde in hetzelfde land, van 1-5+ dagen vóór dag einde in de rest van de wereld.',
                ],
                'max_weight'           => 70,
                'canShipToAP'          => true,
                'canShipToHome'        => true,
            ],
            '65' => [
                'code'                 => '65',
                'name'                 => 'UPS Express Saver ®',
                'originCountries'      => ['BE', 'NL', 'LU', 'FR', 'ES', 'MA'],
                'destinationCountries' => [
                    'BE', 'NL', 'LU', 'FR', 'ES', 'CA', 'UK', 'MX', 'US', 'DE', 'PL', 'IT',
                    'MA',
                ],
                'onlySameAsOrigin'     => false,
                'wsCode'               => 'SV',
                'trackingURL'          => 'http://wwwapps.ups.com/etracking/tracking.cgi?tracknum=@',
                'delays'               => [
                    'fr' => 'Livraison en 1 jour avant la fin de journée dans le même pays, 1 à 5 jours avant la fin de journée pour le reste du monde.',
                    'en' => 'Delivery in 1 day by end of day for deliveries in the same country, 1 to 5 days by end of day in the rest of the world.',
                    'es' => 'Entrega en 1 día al final del día para las entregas en el mismo país, de 1 a 5 días al final del día en el resto del mundo.',
                    'nl' => 'Levering in 1 dag vóór dag einde in hetzelfde land, 1-5 dagen vóór dag einde in de rest van de wereld.',
                ],
                'max_weight'           => 70,
                'canShipToAP'          => true,
                'canShipToHome'        => true,
            ],
            '08' => [
                'code'                 => '08',
                'name'                 => 'UPS Expedited',
                'originCountries'      => ['BE', 'NL', 'LU', 'FR', 'ES'],
                'destinationCountries' => ['BE', 'NL', 'LU', 'FR', 'ES', 'CA', 'UK', 'MX', 'US', 'DE', 'PL', 'IT'],
                'onlySameAsOrigin'     => false,
                'wsCode'               => 'EX',
                'trackingURL'          => 'http://wwwapps.ups.com/etracking/tracking.cgi?tracknum=@',
                'delays'               => [
                    'fr' => 'Livraison en 2 à 5 jours avant la fin de journée.',
                    'en' => 'Delivery in 2 to 5 day by end of day.',
                    'es' => 'Entrega en 2 a 5 días al final del día.',
                    'nl' => 'Levering 2-5 dagen vóór dag einde.',
                ],
                'max_weight'           => 70,
                'canShipToAP'          => true,
                'canShipToHome'        => true,
            ],
            '07' => [
                'code'                 => '07',
                'name'                 => 'UPS Express ®',
                'originCountries'      => ['BE', 'NL', 'LU', 'FR', 'ES'],
                'destinationCountries' => ['BE', 'NL', 'LU', 'FR', 'ES', 'CA', 'UK', 'MX', 'US', 'DE', 'PL', 'IT'],
                'onlySameAsOrigin'     => false,
                'wsCode'               => 'ES',
                'trackingURL'          => 'http://wwwapps.ups.com/etracking/tracking.cgi?tracknum=@',
                'delays'               => [
                    'fr' => 'Livraison en 1 jour avant 10h30 ou 12h dans le même pays, 1 à 3 jours avant 10h30, 12h ou 14h pour le reste du monde.',
                    'en' => 'Delivery in 1 day by 10:30am or noon in the same country, 1 to 3 days by 10:30am, noon or 2pm in the rest of the world.',
                    'es' => 'Entrega en 1 día antes de las 10:30 o 12:00 en el mismo país, de 1-3 días antes de las 14:00 en el resto del mundo.',
                    'nl' => 'Levering in 1 dag door 10:30 of 12u in hetzelfde land, 1-3 dagen voor 10:30, 12u of 14u in de rest van de wereld.',
                ],
                'max_weight'           => 70,
                'canShipToAP'          => true,
                'canShipToHome'        => true,
            ],
            '54' => [
                'code'                 => '54',
                'name'                 => 'UPS Express Plus ®',
                'originCountries'      => ['BE', 'NL', 'LU', 'FR', 'ES'],
                'destinationCountries' => ['BE', 'NL', 'LU', 'FR', 'ES', 'CA', 'UK', 'MX', 'US', 'DE', 'PL', 'IT'],
                'onlySameAsOrigin'     => false,
                'wsCode'               => 'EP',
                'trackingURL'          => 'http://wwwapps.ups.com/etracking/tracking.cgi?tracknum=@',
                'delays'               => [
                    'fr' => 'Livraison en 1 jour avant 8h30 ou 9h pour les livraisons dans le même pays,1 à 3 jours avant 9h pour le reste du monde.',
                    'en' => 'Delivery in 1 day by 8:30am or 9am for deliveries in the same country,1 to 3 days by 9am in the rest of the world.',
                    'es' => 'Entrega en 1 día a las 8:30 o 09:00 en el mismo país,de 1 a 3 días por 09:00 en el resto del mundo.',
                    'nl' => 'Levering in 1 dag van 8:30 of 9:00 voor leveringen in hetzelfde land, 1 tot 3 dagen voor 09:00 in de rest van de wereld.',
                ],
                'max_weight'           => 70,
                'canShipToAP'          => true,
                'canShipToHome'        => true,
            ],
            '70' => [
                'code'                 => '70',
                'name'                 => 'UPS Access Point ™ Economy',
                'originCountries'      => ['BE', 'NL', 'LU', 'FR'],
                'destinationCountries' => ['BE', 'NL', 'LU', 'FR'],
                'onlySameAsOrigin'     => true,
                'wsCode'               => 'LCO',
                'trackingURL'          => 'http://wwwapps.ups.com/etracking/tracking.cgi?tracknum=@',
                'delays'               => [
                    'fr' => 'Livraison en point relais (UPS Access Point ™) en 1 à 2+ jours.',
                    'en' => 'Delivery to an UPS Access Point ™ in 1-2+ days.',
                    'es' => 'Entrega en punto de relevo (UPS Access Point ™) en 1-2+ días.',
                    'nl' => 'Levering in relay punt (UPS Access Point ™) in 1-2 dagen.',
                ],
                'max_weight'           => 20,
                'canShipToAP'          => true,
                'canShipToHome'        => false,
            ],
        ];
    }

    private function getPrepareData(): array
    {
        $data = [];
        foreach ($this->getShippingOptions() as $option) {
            $data[$option['code']] = [
                'name'         => $option['name'],
                'description'  => $option['delays']['en'] ?? '',
                'identifier'   => $option['code'],
                'wsCode'       => $option['wsCode'],
                'data'         => Arr::except($option, ['name', 'code', 'wsCode']),
            ];
        }

        return $data;
    }
}
