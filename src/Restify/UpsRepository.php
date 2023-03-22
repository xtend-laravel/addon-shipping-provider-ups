<?php

namespace XtendLunar\Addons\ShippingProviderUps\Restify;

use XtendLunar\Addons\ShippingProviderUps\Restify\Presenters\UpsPresenter;
use XtendLunar\Addons\RestifyApi\Restify\Repository;

class UpsRepository extends Repository
{
    public static string $presenter = UpsPresenter::class;
}
