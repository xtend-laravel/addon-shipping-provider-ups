<?php

namespace XtendLunar\Addons\ShippingProviderUps\Concerns;

trait WithServicesOrigin
{
    public static function servicesByOrigin(): array
    {
        return [
            'all'   => [
                '96' => __('UPS Worldwide Express Freight'),
                '71' => __('UPS Worldwide Express Freight Midday')
            ],
            'other' => [
                '07' => __('UPS Express'),
                '11' => __('UPS Standard'),
                '08' => __('UPS Worldwide Expedited'),
                '54' => __('UPS Worldwide Express Plus'),
                '65' => __('UPS Worldwide Saver')
            ],
            'PR'    => [
                // Puerto Rico.
                '02' => __('UPS 2nd Day Air'),
                '03' => __('UPS Ground'),
                '01' => __('UPS Next Day Air'),
                '14' => __('UPS Next Day Air Early'),
                '08' => __('UPS Worldwide Expedited'),
                '07' => __('UPS Worldwide Express'),
                '54' => __('UPS Worldwide Express Plus'),
                '65' => __('UPS Worldwide Saver'),
            ],
            'PL'    => [
                // Poland.
                '70' => __('UPS Access Point Economy'),
                '83' => __('UPS Today Dedicated Courrier'),
                '85' => __('UPS Today Express'),
                '86' => __('UPS Today Express Saver'),
                '82' => __('UPS Today Standard'),
                '08' => __('UPS Expedited'),
                '07' => __('UPS Express'),
                '54' => __('UPS Express Plus'),
                '65' => __('UPS Express Saver'),
                '11' => __('UPS Standard'),
            ],
            'MX'    => [
                // Mexico.
                '70' => __('UPS Access Point Economy'),
                '08' => __('UPS Expedited'),
                '07' => __('UPS Express'),
                '11' => __('UPS Standard'),
                '54' => __('UPS Worldwide Express Plus'),
                '65' => __('UPS Worldwide Saver'),
            ],
            'EU'    => [
                // European Union.
                '70' => __('UPS Access Point Economy'),
                '08' => __('UPS Expedited'),
                '07' => __('UPS Express'),
                '11' => __('UPS Standard'),
                '54' => __('UPS Worldwide Express Plus'),
                '65' => __('UPS Worldwide Saver'),
            ],
            'CA'    => [
                // Canada.
                '02' => __('UPS Expedited'),
                '13' => __('UPS Express Saver'),
                '12' => __('UPS 3 Day Select'),
                '70' => __('UPS Access Point Economy'),
                '01' => __('UPS Express'),
                '14' => __('UPS Express Early'),
                '65' => __('UPS Express Saver'),
                '11' => __('UPS Standard'),
                '08' => __('UPS Worldwide Expedited'),
                '07' => __('UPS Worldwide Express'),
                '54' => __('UPS Worldwide Express Plus'),
            ],
            'US'    => [
                // USA.
                '11' => __('UPS Standard'),
                '07' => __('UPS Worldwide Express'),
                '08' => __('UPS Worldwide Expedited'),
                '54' => __('UPS Worldwide Express Plus'),
                '65' => __('UPS Worldwide Saver'),
                '02' => __('UPS 2nd Day Air'),
                '59' => __('UPS 2nd Day Air A.M.'),
                '12' => __('UPS 3 Day Select'),
                '03' => __('UPS Ground'),
                '01' => __('UPS Next Day Air'),
                '14' => __('UPS Next Day Air Early'),
                '13' => __('UPS Next Day Air Saver'),
            ]
        ];
    }

    public static function getServicesForCountry(string $countryCode)
    {
        $services = static::servicesByOrigin();
        $servicesForCountry = [];

        if (isset($services[$countryCode])) {
            $servicesForCountry = $services[$countryCode];
        }

        if ($countryCode !== 'PL' && in_array($countryCode, static::euCountriesCodes())) {
            $servicesForCountry = $services['EU'];
        }

        if (count($servicesForCountry) === 0) {
            $servicesForCountry = $services['other'];
        }

        foreach ($services['all'] as $key => $value) {
            $servicesForCountry[$key] = $value;
        }

        return $servicesForCountry;
    }

    public static function euCountriesCodes(): array
    {
        return [
            "AT", // Austria
            "BE", // Belgium
            "BG", // Bulgaria
            "HR", // Croatia
            "CY", // Cyprus
            "CZ", // Czech Republic
            "DK", // Denmark
            "EE", // Estonia
            "FI", // Finland
            "FR", // France
            "DE", // Germany
            "GR", // Greece
            "HU", // Hungary
            "IE", // Ireland
            "IT", // Italy
            "LV", // Latvia
            "LT", // Lithuania
            "LU", // Luxembourg
            "MT", // Malta
            "NL", // Netherlands
            "PL", // Poland
            "PT", // Portugal
            "RO", // Romania
            "SK", // Slovakia
            "SI", // Slovenia
            "ES", // Spain
            "SE", // Sweden
        ];
    }
}