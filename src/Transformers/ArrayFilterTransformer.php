<?php

namespace XtendLunar\Addons\ShippingProviderUps\Transformers;

use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Transformers\Transformer;

class ArrayFilterTransformer implements Transformer
{
    public function transform(DataProperty $property, mixed $value): array
    {
        /** @var \Illuminate\Contracts\Support\Arrayable $value */
        return $this->filterNullRecursive($value->toArray());
    }

    protected function filterNullRecursive($array): array
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = static::filterNullRecursive($value);
            }
        }
        return array_filter($array, function ($value) {
            return !is_null($value);
        });
    }
}
