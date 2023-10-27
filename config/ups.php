<?php

return [
    'client_id'     => env('UPS_CLIENT_ID'),
    'client_secret' => env('UPS_CLIENT_SECRET'),
    'is_sandbox'    => env('UPS_SANDBOX_MODE'),
    'shipper'       => [
        'shipper_number' => env('UPS_SHIPPER_NUMBER'),
        'address'        => [
            'address_line' => [
                '4331 Marietta Street'
            ],
            'city'         => 'Santa Rosa',
            'state_code'   => 'CA',
            'postal_code'  => '94565',
            'country_code' => 'US',
        ]
    ],
    'ship_from'     => [
        'name'    => 'Ship From Name',
        'address' => [
            'address_line' => [
                '4331 Marietta Street'
            ],
            'city'         => 'Santa Rosa',
            'state_code'   => 'CA',
            'postal_code'  => '94565',
            'country_code' => 'US',
        ]
    ],
    'packaging'     => [
        'type'        => \XtendLunar\Addons\ShippingProviderUps\Enums\PackagingType::Package->value,
        'weight_unit' => \XtendLunar\Addons\ShippingProviderUps\Enums\UnitOfMeasurement::Lbs->value,
        'unit_of_measurement' => \XtendLunar\Addons\ShippingProviderUps\Enums\UnitOfMeasurement::Cm->value,
    ]
];
