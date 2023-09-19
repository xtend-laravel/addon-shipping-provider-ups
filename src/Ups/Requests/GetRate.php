<?php

namespace XtendLunar\Addons\ShippingProviderUps\Ups\Requests;

use Saloon\Http\Request;

class GetRate extends Request
{
    public function resolveEndpoint(): string
    {
        return '/rating/';
    }
}