<?php

namespace LaravelResourceHelpers\Traits;

use Illuminate\Http\Resources\Json\JsonResource;
use LaravelResourceHelpers\ResourceHelpers;

/**
 * 
 * @mixin JsonResource
 */
trait WithDatetime
{
    use WithOptional;

    protected function datetime(string $key): mixed
    {
        $value = $this->{$key};
        return $value ? $value->format(ResourceHelpers::datetimeFormat()) : null;
    }

    protected function optionalDatetime(string $key): mixed
    {
        $datetimeFormat = ResourceHelpers::datetimeFormat();

        return $this->optional($key, fn($value) => $value ? $value->format($datetimeFormat) : null);
    }
}