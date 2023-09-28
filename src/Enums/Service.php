<?php

namespace XtendLunar\Addons\ShippingProviderUps\Enums;

enum Service: string
{
    // all
    case UPS_WORLDWIDE_EXPRESS_FREIGHT = '96';
    case UPS_WORLDWIDE_EXPRESS_FREIGHT_MIDDAY = '71';

    // other
    case UPS_EXPRESS = '07';
    case UPS_STANDARD = '11';
    case UPS_WORLDWIDE_EXPEDITED = '08';
    case UPS_WORLDWIDE_EXPRESS_PLUS = '54';
    case UPS_WORLDWIDE_SAVER = '65';

    case UPS_SECOND_DAY_AIR = '02';
    case UPS_GROUND = '03';
    case UPS_NEXT_DAY_AIR = '01';
    case UPS_NEXT_DAY_AIR_EARLY = '14';

    case UPS_ACCESS_POINT_ECONOMY = '70';
    case UPS_TODAY_DEDICATED_COURIER = '83';
    case UPS_TODAY_EXPRESS = '85';
    case UPS_TODAY_EXPRESS_SAVER = '86';
    case UPS_TODAY_STANDARD = '82';
    case UPS_DAY_SELECT = '12';

    case UPS_SECOND_DAY_AIR_AM = '59';
    case UPS_NEXT_DAY_AIR_SAVER = '13';

    public function description(): string
    {
        return match ($this) {
            self::UPS_WORLDWIDE_EXPRESS_FREIGHT => 'UPS Worldwide Express Freight',
            self::UPS_WORLDWIDE_EXPRESS_FREIGHT_MIDDAY => 'UPS Worldwide Express Freight Midday',
            self::UPS_EXPRESS => 'UPS Express',
            self::UPS_STANDARD => 'UPS Standard',
            self::UPS_WORLDWIDE_EXPEDITED => 'UPS Worldwide Expedited',
            self::UPS_WORLDWIDE_EXPRESS_PLUS => 'UPS Worldwide Express Plus',
            self::UPS_WORLDWIDE_SAVER => 'UPS Worldwide Saver',
            self::UPS_SECOND_DAY_AIR => 'UPS Second Day Air',
            self::UPS_GROUND => 'UPS Ground',
            self::UPS_NEXT_DAY_AIR => 'UPS Next Day Air',
            self::UPS_NEXT_DAY_AIR_EARLY => 'UPS Next Day Air Early',
            self::UPS_ACCESS_POINT_ECONOMY => 'UPS Access Point Economy',
            self::UPS_TODAY_DEDICATED_COURIER => 'UPS Today Dedicated Courier',
            self::UPS_TODAY_EXPRESS => 'UPS Today Express',
            self::UPS_TODAY_EXPRESS_SAVER => 'UPS Today Express Saver',
            self::UPS_TODAY_STANDARD => 'UPS Today Standard',
            self::UPS_DAY_SELECT => 'UPS Day Select',
            self::UPS_SECOND_DAY_AIR_AM => 'UPS Second Day Air AM',
            self::UPS_NEXT_DAY_AIR_SAVER => 'UPS Next Day Air Saver',
            default => throw new \Exception('Unknown service code: ' . $this->value),
        };
    }
}
