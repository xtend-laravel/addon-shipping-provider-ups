<?php

namespace XtendLunar\Addons\ShippingProviderUps\Ups\Requests\Rating;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetRate extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @var string The request option to use. Valid options are: Shop, Rate
     */
    protected string $requestOption;

    public function resolveEndpoint(): string
    {
        return '/rating/v1/' . $this->requestOption;
    }

    public function __construct(string $requestOption = 'Shop')
    {
        $this->requestOption = $requestOption;
    }
}
