<?php

namespace XtendLunar\Addons\ShippingProviderUps\Restify\Presenters;

use XtendLunar\Addons\RestifyApi\Restify\Contracts\Presentable;
use Binaryk\LaravelRestify\Http\Requests\RestifyRequest;
use XtendLunar\Addons\RestifyApi\Restify\Presenters\PresenterResource;

class UpsPresenter extends PresenterResource implements Presentable
{
    public function transform(RestifyRequest $request): array
    {
        return $this->data;
    }
}
