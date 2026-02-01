<?php

namespace LaravelResourceHelpers\Traits;

use Illuminate\Http\Resources\Json\JsonResource;
use LaravelResourceHelpers\ResourceHelpers;

/**
 * 
 * @mixin JsonResource
 */
trait WithDate
{
    use WithOptional;

    protected function date(string $key): mixed
    {
        $value = $this->{$key};
        return $value ? $value->format(ResourceHelpers::dateFormat()) : null;
    }

    protected function optionalDate(string $key): mixed
    {
        $dateFormat = ResourceHelpers::dateFormat();

        return $this->optional($key, fn($value) => $value ? $value->format($dateFormat) : null);
    }
}