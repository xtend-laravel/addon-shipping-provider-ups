<?php

namespace XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Shipping;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class LabelRecovery extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return '/labels/v1/recovery';
    }
}
